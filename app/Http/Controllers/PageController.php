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
        $allFoods = $this->getFoods();
        $user = $this->getUsers();
        $search = $request->query('search');

        if ($search) {
            $foods = array_filter($allFoods, function ($food) use ($search) {
                return str_contains(strtolower($food['name']), strtolower($search));
            });
        } else {
            $foods = $allFoods;
        }

        if ($request->has('location')) {
            session(['user_location' => $request->query('location')]);
        }

        $location = session('user_location');

        return view('page.menu', compact('foods', 'search', 'location', 'user'));
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

    public function cart()
    {
        $user = $this->getUsers();

        return view('page.cart', compact('user'));
    }

    private function getFoods()
    {
        return [
            [
                'id' => 1,
                'name' => 'Signature Burger',
                'price' => 189,
                'description' => 'Wagyu beef with double cheddar, caramelized onions, and our secret special sauce.',
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 2,
                'name' => 'Rustic Pizza',
                'price' => 499,
                'description' => 'Hand-tossed sourdough with fresh basil, buffalo mozzarella, and sun-ripened cherry tomatoes.',
                'image' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 3,
                'name' => 'Harvest Bowl',
                'price' => 249,
                'description' => 'A vibrant mix of organic greens, roasted quinoa, chickpeas, and a honey-lemon tahini dressing.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 4,
                'name' => 'Pesto Pasta',
                'price' => 299,
                'description' => 'Creamy basil pesto with roasted pine nuts.',
                'image' => 'https://images.unsplash.com/photo-1473093226795-af9932fe5856?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 5,
                'name' => 'Berry Pancakes',
                'price' => 99,
                'description' => 'Fluffy buttermilk pancakes topped with organic maple syrup and fresh seasonal berries.',
                'image' => 'https://images.unsplash.com/photo-1528207776546-365bb710ee93?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 6,
                'name' => 'Street Tacos',
                'price' => 149,
                'description' => 'Three soft corn tortillas with slow-cooked carnitas, pickled onions, and fresh cilantro.',
                'image' => 'https://images.unsplash.com/photo-1552332386-f8dd00dc2f85?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 7,
                'name' => 'Signature Burger',
                'price' => 189,
                'description' => 'Wagyu beef with double cheddar, caramelized onions, and our secret special sauce.',
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 8,
                'name' => 'Rustic Pizza',
                'price' => 499,
                'description' => 'Hand-tossed sourdough with fresh basil, buffalo mozzarella, and sun-ripened cherry tomatoes.',
                'image' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 9,
                'name' => 'Harvest Bowl',
                'price' => 249,
                'description' => 'A vibrant mix of organic greens, roasted quinoa, chickpeas, and a honey-lemon tahini dressing.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 10,
                'name' => 'Pesto Pasta',
                'price' => 299,
                'description' => 'Creamy basil pesto with roasted pine nuts.',
                'image' => 'https://images.unsplash.com/photo-1473093226795-af9932fe5856?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 11,
                'name' => 'Berry Pancakes',
                'price' => 99,
                'description' => 'Fluffy buttermilk pancakes topped with organic maple syrup and fresh seasonal berries.',
                'image' => 'https://images.unsplash.com/photo-1528207776546-365bb710ee93?q=80&w=500&auto=format&fit=crop'
            ],
            [
                'id' => 12,
                'name' => 'Street Tacos',
                'price' => 149,
                'description' => 'Three soft corn tortillas with slow-cooked carnitas, pickled onions, and fresh cilantro.',
                'image' => 'https://images.unsplash.com/photo-1552332386-f8dd00dc2f85?q=80&w=500&auto=format&fit=crop'
            ],
        ];
    }

    private function getUsers()
    {
        $savedLocation = session('user_location', 'No address set yet');

        return [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan.delacruz@email.com',
            'phone' => '+63 912 345 6789',
            'address' => $savedLocation,
            'joined' => 'December 2024',
            'profile_pix' => 'https://ui-avatars.com/api/?name=Juan+Dela+Cruz&background=ff6b6b&color=fff'
        ];
    }
}
