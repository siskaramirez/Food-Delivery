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

        User::where('userid', Auth::id())->update([
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
            ->select('orders.*', 'order_status.status_name as status')
            ->orderBy('orders.orderid', 'desc')
            ->get();

        $drivers = DB::table('driver')->where('isAvailable', 'AV')->get();

        return view('page.orders', compact('user', 'orders', 'drivers'));
    }

    public function cart()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('signin.page');
        }

        return view('page.cart', compact('user'));
    }

    public function checkout()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('signin.page');
        }

        $activeOrdersCount = DB::table('orders')
            ->where('userid', $user->userid)
            ->whereNotIn('order_status_id', [1, 2, 3])
            ->count();

        if ($activeOrdersCount >= 2) {
            return redirect()->route('cart.page')->with('error', 'You currently have 2 active orders.');
        }

        return view('page.checkout', compact('user'));
    }

    public function storeOrder(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthorized']);

        // Double check the limit before inserting to DB
        $activeOrders = DB::table('orders')
            ->where('userid', $user->userid)
            ->whereIn('order_status_id', [1, 2, 3])
            ->count();

        if ($activeOrders >= 2) {
            return response()->json(['success' => false, 'message' => 'Order limit reached (Max 2).']);
        }

        try {
            DB::beginTransaction();

            // 1. Insert sa Orders table
            // deliveryneeded: 1 if Delivery, 0 if Pick-up
            $deliveryNeeded = ($request->service === 'Delivery') ? 1 : 0;

            $orderId = DB::table('orders')->insertGetId([
                'userid'            => $user->userid,
                'orderdate'         => now(),
                'paymentstatus'     => 'Pending',
                'order_status_id'   => 1, // Default: Preparing
                'deliveryneeded'    => $deliveryNeeded,
                'datelastmodified'  => now(),
            ]);

            // 2. Loop sa Cart items at gamitin ang Stored Procedure
            foreach ($request->cart as $item) {
                // A. I-check muna ang stock at bawasan gamit ang iyong Stored Procedure
                // 'S' stands for Sell/Subtract
                $stockResult = DB::select('CALL stored_foodbuy_sell(?, ?, ?)', [
                    $item['id'],
                    'S',
                    $item['qty']
                ]);

                $message = $stockResult[0]->message;

                // B. Kung nag-error ang procedure (e.g., No stock or insufficient)
                if ($message !== 'Order received') {
                    throw new \Exception("Item " . $item['name'] . ": " . $message);
                }

                // C. Kung OK ang stock, saka i-insert sa order_items
                DB::select('CALL insert_orderitem(?, ?, ?)', [
                    $orderId,
                    $item['id'],
                    $item['qty']
                ]);
            }

            $paymentStatus = ($request->mop === 'Cash on Delivery (COD)') ? 'Pending' : 'Paid';

            // 3. Insert sa Payments (Triggering the 'Paid' status change)
            DB::table('payments')->insert([
                'orderid'       => $orderId,
                'paymentmethod' => $request->mop,
                'paymentstatus' => $paymentStatus,
                'reference'     => $request->ref, // Ang MOCK-REF mula sa JS
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
