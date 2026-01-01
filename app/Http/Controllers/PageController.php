<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $user = $this->getUsers();

        return view('page.home', compact('user'));
    }

    public function menu(Request $request)
    {
        $allFoods = collect($this->getFoods());
        $user = $this->getUsers();
        $search = $request->query('search');

        if ($search) {
            $filtered = $allFoods->filter(function ($food) use ($search) {
                return str_contains(strtolower($food['name']), strtolower($search));
            });
        } else {
            $filtered = $allFoods;
        }
        $categories = $filtered->groupBy('category');

        if ($request->has('location')) {
            session(['user_location' => $request->query('location')]);
        }
        $location = session('user_location');

        return view('page.menu', compact('categories', 'search', 'location', 'user'));
    }

    public function show($id)
    {
        $foods = $this->getFoods();
        $food = null;

        foreach ($foods as $f) {
            if ($f['id'] == $id) {
                $food = $f;
                break;
            }
        }

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
            'name' => 'required|string|max:255',
            'address' => 'required',
            'phone' => 'required|numeric|digits:11',
        ];

        $messages = [
            'phone.numeric'  => 'The phone field must contain numbers only.',
            'phone.digits'   => 'The phone field must be 11 digits.',
        ];

        $request->validate($rules, $messages);

        return redirect()->route('profile.page');
    }

    public function orders()
    {
        $user = $this->getUsers();
        $driver = $this->getDrivers();

        return view('page.orders', compact('user', 'driver'));
    }

    public function cart()
    {
        $user = $this->getUsers();

        return view('page.cart', compact('user'));
    }

    public function checkout()
    {
        $user = $this->getUsers();
        $driver = $this->getDrivers();

        return view('page.checkout', compact('user', 'driver'));
    }

    private function getFoods()
    {
        return [
            [
                'id' => 1,
                'name' => 'Signature Burger',
                'category' => 'food',
                'price' => 189,
                'description' => 'Wagyu beef with double cheddar, caramelized onions, and our secret special sauce.',
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 2,
                'name' => 'Rustic Pizza',
                'category' => 'food',
                'price' => 499,
                'description' => 'Hand-tossed sourdough with fresh basil, buffalo mozzarella, and sun-ripened cherry tomatoes.',
                'image' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 3,
                'name' => 'Harvest Bowl',
                'category' => 'food',
                'price' => 249,
                'description' => 'A vibrant mix of organic greens, roasted quinoa, chickpeas, and a honey-lemon tahini dressing.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 4,
                'name' => 'Pesto Pasta',
                'category' => 'food',
                'price' => 299,
                'description' => 'Creamy basil pesto with roasted pine nuts and Parmesan cheese.',
                'image' => asset('images/pestopasta.jpg')
            ],
            [
                'id' => 5,
                'name' => 'Berry Pancakes',
                'category' => 'food',
                'price' => 99,
                'description' => 'Fluffy buttermilk pancakes topped with organic maple syrup and fresh seasonal berries.',
                'image' => 'https://images.unsplash.com/photo-1528207776546-365bb710ee93?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 6,
                'name' => 'Street Tacos',
                'category' => 'food',
                'price' => 149,
                'description' => 'Three soft corn tortillas with slow-cooked carnitas, pickled onions, and fresh cilantro.',
                'image' => 'https://images.unsplash.com/photo-1552332386-f8dd00dc2f85?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 7,
                'name' => 'Lemon Iced Tea',
                'category' => 'drink',
                'price' => 59,
                'description' => 'Refreshing house-blend tea with a zesty lemon kick.',
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 8,
                'name' => 'Coca Cola',
                'category' => 'drink',
                'price' => 49,
                'description' => 'Classic carbonated soft drink served ice cold.',
                'image' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 9,
                'name' => 'Mango Frosty',
                'category' => 'drink',
                'price' => 79,
                'description' => 'Blended fresh mangoes with a creamy velvety texture.',
                'image' => asset('images/mango.jpg')
            ],
            [
                'id' => 10,
                'name' => 'Banana Frosty',
                'category' => 'drink',
                'price' => 79,
                'description' => 'A sweet and chilling blend of ripe bananas and milk with honey.',
                'image' => asset('images/banana.jpg')
            ],
            [
                'id' => 11,
                'name' => 'Chocolate Shake',
                'category' => 'drink',
                'price' => 84,
                'description' => 'Rich Belgian chocolate blended with premium vanilla ice cream.',
                'image' => asset('images/chocolate.jpg')
            ],
            [
                'id' => 12,
                'name' => 'Cocktail',
                'category' => 'drink',
                'price' => 109,
                'description' => 'Alcoholic mixed drink consisting of one or more spirits combined with other ingredients.',
                'image' => asset('images/cocktail.jpg')
            ],
            [
                'id' => 13,
                'name' => 'Halo-Halo',
                'category' => 'dessert',
                'price' => 69,
                'description' => 'A festive Filipino dessert with crushed ice, evaporated milk, and various sweet beans.',
                'image' => asset('images/halohalo.jpg')
            ],
            [
                'id' => 14,
                'name' => 'Banana Split',
                'category' => 'dessert',
                'price' => 79,
                'description' => 'Fresh bananas topped with three scoops of ice cream and chocolate drizzle.',
                'image' => asset('images/bananasplit.jpg')
            ],
            [
                'id' => 15,
                'name' => 'Strawberry Cheesecake',
                'category' => 'dessert',
                'price' => 119,
                'description' => 'Creamy New York style cheesecake with fresh strawberry compote.',
                'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?q=80&w=500&auto=format&fit=crop'
            ],
        ];
    }

    private function getUsers()
    {
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
