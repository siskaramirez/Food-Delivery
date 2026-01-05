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
                ->where('orders.userid', $user->userid)
                ->select('orders.*', 'order_status.status_name', 'payments.paymentstatus')
                ->orderBy('orders.orderdate', 'desc')
                ->get();
        }

        return view('admin.home', compact('users'));
    }

    public function deleteUser($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $orderIds = DB::table('orders')->where('userid', $id)->pluck('orderid');

                DB::table('order_items')->whereIn('orderid', $orderIds)->delete();
                DB::table('payments')->whereIn('orderid', $orderIds)->delete();
                DB::table('delivery')->where('orderid', $id)->delete();
                DB::table('deliveryhistorylog')->where('orderid', $id)->delete();
                //DB::table('orderhistorylog')->where('orderid', $id)->delete();

                DB::table('orders')->where('userid', $id)->delete();

                DB::table('users')->where('userid', $id)->delete();
            });

            return redirect()->back()->with('success', 'Account and all their records deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete account.');
        }
    }

    public function updateOrder(Request $request, $id)
    {
        $request->validate([
            'order_status'    => 'required|string',
            'payment_status'  => 'required|string',
            'delivery_status' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                // A. Update Order Status (Table: orders)
                // Kinukuha natin ang status_id base sa status_name na pinili sa dropdown
                $status = DB::table('order_status')->where('status_name', $request->order_status)->first();
                if ($status) {
                    DB::table('orders')->where('orderid', $id)->update([
                        'order_status_id' => $status->order_status_id
                    ]);
                }

                // B. Update Payment Status (Table: payments)
                DB::table('payments')->where('orderid', $id)->update([
                    'paymentstatus' => $request->payment_status
                ]);

                // C. Update Delivery Status kung mayroon (Table: delivery)
                if ($request->has('delivery_status')) {
                    DB::table('delivery')->where('orderid', $id)->update([
                        'status' => $request->delivery_status
                    ]);
                }
            });

            return redirect()->back()->with('success', "Order #{$id} updated successfully!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Failed to update order. Please try again.");
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
            ->select(
                'order_items.orderid',
                'order_items.foodcode',
                'order_items.quantity',
                'order_items.totalprice',
                'orders.deliveryneeded',
                'order_status.status_name as order_status',
                'payments.paymentstatus',
                'payments.paymentmethod',
                'orders.orderdate',
                'delivery.deliverystatus'
            )
            ->when($search, function ($query, $search) {
                return $query->where('order_items.orderid', 'like', "%{$search}%");
            })
            ->orderBy('orders.orderdate', 'desc')
            ->get();

        return view('admin.orders', compact('transactions'));
    }

    public function drivers(Request $request)
    {
        if (session('user_role') !== 'admin') {
            return redirect()->route('signin.page');
        }

        $search = $request->query('search');

        $drivers = DB::table('driver')
            ->when($search, function ($query, $search) {
                return $query->where('drivername', 'like', "%{$search}%")
                    ->orWhere('license', 'like', "%{$search}%");
            })
            ->get();

        return view('admin.drivers', compact('drivers'));
    }
}
