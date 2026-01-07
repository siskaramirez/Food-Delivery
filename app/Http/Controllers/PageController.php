<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function home()
    {
        $user = $this->getUsers();

        return view('page.home', compact('user'));
    }

    public function menu(Request $request)
    {
        $search = $request->query('search');

        $query = DB::table('food_items');

        if ($search) {
            $query->where('foodname', 'LIKE', '%' . $search . '%');
        }

        $allFoods = $query->where('isAvailable', 'AV')->get();

        $categories = $allFoods->groupBy('category');

        $user = Auth::check() ? Auth::user() : null;

        return view('page.menu', compact('categories', 'search', 'user'));
    }

    public function show($id)
    {
        $food = DB::table('food_items')->where('foodcode', $id)->first();

        if (!$food) {
            abort(404);
        }

        return view('page.show', compact('food'));
    }

    public function about()
    {
        $user = $this->getUsers();

        return view('page.about', compact('user'));
    }

    public function profile()
    {
        $user = $this->getUsers();

        return view('page.profile', compact('user'));
    }

    public function edit()
    {
        $user = $this->getUsers();

        return view('page.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:50',
            'address' => 'required|max:100',
            'phone' => 'required|numeric|digits:11|regex:/^09/',
        ];

        $messages = [
            'phone.numeric'  => 'The phone field must contain numbers only.',
            'phone.digits'   => 'The phone field must be 11 digits.',
            'phone.regex'   => 'The phone number must start with 09.',
        ];

        $request->validate($rules, $messages);

        User::where('username', Auth::user()->username)->update([
            'uname'     => $request->name,
            'address'   => $request->address,
            'contactno' => $request->phone,
        ]);

        return redirect()->route('profile.page')->with('success', 'Profile updated!');
    }

    public function orders()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('signin.page');
        }

        $orders = DB::table('orders')
            ->join('order_status', 'orders.order_status_id', '=', 'order_status.order_status_id')
            ->leftJoin('payments', 'orders.orderid', '=', 'payments.orderid')
            ->where('orders.userid', $user->userid)
            ->where('orders.order_status_id', 1)
            ->select('orders.*', 'order_status.status_name as status')
            ->orderBy('orders.orderid', 'desc')
            ->get();

        $drivers = DB::table('driver')->where('isAvailable', 'AV')->get();

        return view('page.orders', compact('user', 'orders', 'drivers'));
    }

    public function cancelOrder($id)
    {
        try {
            DB::beginTransaction();

            DB::table('order_items')->where('orderid', $id)->delete();
            DB::table('payments')->where('orderid', $id)->delete();
            DB::table('delivery')->where('orderid', $id)->delete();
            //DB::table('orderhistorylog')->where('orderid', $id)->delete();
            DB::table('deliveryhistorylog')->where('orderid', $id)->delete();

            $deleted = DB::table('orders')->where('orderid', $id)->delete();

            if ($deleted) {
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Order and all related records deleted.']);
            }
            throw new \Exception("Order not found or already deleted.");
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function history()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('signin.page');
        }

        $orders = DB::table('orders')
            ->join('order_status', 'orders.order_status_id', '=', 'order_status.order_status_id')
            ->leftJoin('payments', 'orders.orderid', '=', 'payments.orderid')
            ->leftJoin('order_items', 'orders.orderid', '=', 'order_items.orderid')
            ->leftJoin('delivery', 'orders.orderid', '=', 'delivery.orderid')
            ->leftJoin('driver', 'delivery.license', '=', 'driver.license')
            ->where('orders.userid', $user->userid)
            ->whereIn('orders.order_status_id', [2, 3])
            ->select(
                'orders.orderid',
                'orders.datelastmodified',
                'orders.order_status_id',
                'payments.paymentmethod',
                'driver.drivername',
                'driver.contactno',
                'order_status.status_name as status',

                DB::raw('SUM(order_items.totalprice) as totalprice')
            )
            ->groupBy(
                'orders.orderid',
                'orders.datelastmodified',
                'orders.order_status_id',
                'payments.paymentmethod',
                'driver.drivername',
                'driver.contactno',
                'order_status.status_name'
            )
            ->orderBy('orders.orderid', 'desc')
            ->get();

        return view('page.history', compact('user', 'orders'));
    }

    public function cart()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('signin.page');
        }

        $activeOrdersCount = DB::table('orders')
            ->where('userid', $user->userid)
            ->where('order_status_id', [1])
            ->count();

        $foodStocks = DB::table('food_items')
            ->pluck('quantity', 'foodcode')
            ->toArray();

        $cart = [];

        return view('page.cart', compact('user', 'activeOrdersCount', 'cart', 'foodStocks'));
    }

    public function checkout()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('signin.page');
        }

        return view('page.checkout', compact('user'));
    }

    public function storeOrder(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthorized']);

        $activeOrders = DB::table('orders')
            ->where('userid', $user->userid)
            ->whereIn('order_status_id', [1, 2, 3])
            ->count();

        if ($activeOrders >= 2) {
            return response()->json(['success' => false, 'message' => 'Order limit reached (Max 2).']);
        }

        try {
            DB::beginTransaction();

            $deliveryNeeded = ($request->service === 'Delivery') ? 1 : 0;
            $paymentStatus = ($request->mop === 'Cash on Delivery') ? 'Pending' : 'Paid';

            $orderId = DB::table('orders')->insertGetId([
                'userid'            => $user->userid,
                'orderdate'         => now(),
                'paymentstatus'     => $paymentStatus,
                'order_status_id'   => 1,
                'deliveryneeded'    => $deliveryNeeded,
                'datelastmodified'  => now(),
            ]);

            foreach ($request->cart as $item) {
                $stockResult = DB::select('CALL stored_foodbuy_sell(?, ?, ?)', [
                    $item['id'],
                    'S',
                    $item['qty']
                ]);

                $message = $stockResult[0]->message;

                if ($message !== 'Order received') {
                    throw new \Exception("Item " . $item['name'] . ": " . $message);
                }

                DB::select('CALL insert_orderitem(?, ?, ?)', [
                    $orderId,
                    $item['id'],
                    $item['qty']
                ]);
            }

            $finalReference = ($request->mop === 'Cash on Delivery (COD)')
                ? 'COD-PAYMENT'
                : $request->ref;

            DB::table('payments')->insert([
                'orderid'       => $orderId,
                'paymentmethod' => $request->mop,
                'paymentstatus' => $paymentStatus,
                'reference'     => $finalReference,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    private function getUsers()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return [
                'name'        => $user->uname,
                'email'       => $user->username,
                'phone'       => $user->contactno,
                'address'     => $user->address,
                'joined'      => date('F Y', strtotime($user->dateregistered)),
                'profile_pix' => asset('images/profile.jpg')
            ];
        }

        return [
            'name' => 'Guest User',
            'email' => 'No email address set yet',
            'phone' => 'No phone number set yet',
            'address' => 'No address set yet',
            'joined' => date('F Y'),
            'profile_pix' => asset('images/profile.jpg')
        ];
    }

    private function getDrivers()
    {
        return [
            'name' => "Ricardo Dalisay",
            'phone' => "09175550123",
            'plate' => "ABC 1234",
            'distance' => "1.2 km",
            'eta' => "15 mins",
            'status' => "Preparing"
        ];
    }
}
