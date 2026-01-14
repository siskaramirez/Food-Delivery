<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function home(Request $request)
    {
        if (session('user_role') !== 'admin') {
            return redirect()->route('signin.page');
        }

        $search = $request->query('search');

        $users = DB::table('users')
            ->when($search, function ($query, $search) {
                return $query->where('uname', 'like', "%{$search}%")
                    ->orWhere('userid', 'like', "%{$search}%");
            })
            ->get();

        foreach ($users as $user) {
            $user->orders = DB::table('orders')
                ->join('order_status', 'orders.order_status_id', '=', 'order_status.order_status_id')
                ->join('payments', 'orders.orderid', '=', 'payments.orderid')
                ->leftJoin('delivery', 'orders.orderid', '=', 'delivery.orderid')
                ->where('orders.userid', $user->userid)
                ->select('orders.*', 'orders.deliveryneeded', 'order_status.status_name', 'payments.paymentstatus', 'delivery.deliverystatus')
                ->orderBy('orders.orderid', 'desc')
                ->get();
        }

        return view('admin.home', compact('users'));
    }

    public function updateOrder(Request $request, $id)
    {
        $request->validate([
            'order_status'    => 'required|string',
            'payment_status'  => 'nullable|string',
            'delivery_status' => 'nullable|string',
            'driver_license'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $id) {
            //$order = DB::table('orders')->where('orderid', $id)->first();
            $status = DB::table('order_status')->where('status_name', $request->order_status)->first();

            if ($status) {
                DB::table('orders')->where('orderid', $id)->update([
                    'order_status_id' => $status->order_status_id,
                    'datelastmodified' => now()
                ]);
            }

            if ($request->filled('payment_status')) {
                DB::table('orders')->where('orderid', $id)->update([
                    'paymentstatus' => $request->payment_status
                ]);

                DB::table('payments')->where('orderid', $id)->update([
                    'paymentstatus' => $request->payment_status,
                    'updated_at' => now()
                ]);
            }

            if ($request->has('driver_license')) {
                DB::table('delivery')->where('orderid', $id)->update([
                    'license' => $request->driver_license,
                    'deliverystatus' => $request->delivery_status
                ]);
            }
        });

        return back()->with('success', 'Order updated successfully.');
    }
    
    public function updateStatus($license)
    {
        $driver = DB::table('driver')->where('license', $license)->first();

        if ($driver) {
            $newStatus = ($driver->isAvailable == 'AV') ? 'UA' : 'AV';

            DB::table('driver')->where('license', $license)->update(['isAvailable' => $newStatus]);
        }

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function addQuantity(Request $request, $id)
    {
        $request->validate([
            'new_quantity' => 'required|integer|min:1'
        ]);

        try {
            DB::table('food_items')->where('foodcode', $id)->increment('quantity', $request->new_quantity);

            return redirect()->back()->with('success', 'Quantity updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update quantity.');
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing.page');
    }

    public function orders(Request $request)
    {
        if (session('user_role') !== 'admin') {
            return redirect()->route('signin.page');
        }

        $search = $request->query('search');

        $transactions = DB::table('order_items')
            ->join('orders', 'order_items.orderid', '=', 'orders.orderid')
            ->join('order_status', 'orders.order_status_id', '=', 'order_status.order_status_id')
            ->join('payments', 'orders.orderid', '=', 'payments.orderid')
            ->leftJoin('delivery', 'orders.orderid', '=', 'delivery.orderid')
            ->leftJoin('driver', 'delivery.license', '=', 'driver.license')
            ->select(
                'order_items.orderid',
                'order_items.foodcode',
                'order_items.quantity',
                'order_items.totalprice',
                'orders.deliveryneeded',
                'orders.order_status_id',
                'order_status.status_name',
                'payments.paymentstatus',
                'payments.paymentmethod',
                'orders.orderdate',
                'delivery.deliverystatus',
                'delivery.license'
            )
            ->when($search, function ($query, $search) {
                return $query->where('order_items.orderid', 'like', "%{$search}%");
            })
            ->orderBy('order_items.orderid', 'desc')
            ->get();

        $drivers = DB::table('driver')->select('license', 'isAvailable')->get();

        return view('admin.orders', compact('transactions', 'drivers'));
    }

    public function menu(Request $request)
    {
        if (session('user_role') !== 'admin') {
            return redirect()->route('signin.page');
        }

        $search = $request->query('search');

        $allFoods = DB::table('food_items')
            ->when($search, function ($query, $search) {
                return $query->where('foodname', 'LIKE', '%' . $search . '%')
                    ->orWhere('foodcode', 'LIKE', '%' . $search . '%');
            })
            ->get();

        $categories = $allFoods->groupBy('category');

        $user = Auth::check() ? Auth::user() : null;

        return view('admin.menu', compact('categories', 'search', 'user'));
    }

    public function drivers(Request $request)
    {
        if (session('user_role') !== 'admin') {
            return redirect()->route('signin.page');
        }

        $search = $request->query('search');

        $drivers = DB::table('driver')
            ->select(
                'license',
                'drivername',
                'contactno',
                'plateno',
                'isAvailable',
                DB::raw("(SELECT COUNT(*) FROM delivery
                  WHERE license = driver.license 
                  AND deliverystatus IN ('Assigned', 'Picked Up', 'En Route')) as active_orders_count")
            )
            ->when($search, function ($query, $search) {
                return $query->where('driver.drivername', 'like', "%{$search}%")
                    ->orWhere('driver.license', 'like', "%{$search}%");
            })
            ->get();

        return view('admin.drivers', compact('drivers'));
    }
}
