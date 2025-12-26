<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function landing()
    {
        return view('auth.landing');
    }

    public function showSignup()
    {
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'fname'     => 'required|string|max:100',
            'lname'     => 'required|string|max:100',
            'email'     => 'required|email',
            'bday'      => 'required|date',
            'address'   => 'required|string',
            'password'  => 'required|min:8',
            'gender'    => 'required|in:male,female,other',
        ]);

        return redirect()->route('home.page');
    }

    public function showSignin()
    {
        return view('auth.signin');
        /*$isForgotPass = request()->routeIs('password.request');

        return view('auth.signin', compact('isForgotPass'));*/
    }

    public function signin(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'password'   => 'required|min:8',
        ]);

        return redirect()->route('home.page');
    }

    
}
