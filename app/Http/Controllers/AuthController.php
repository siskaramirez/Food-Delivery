<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function landing() {
        return view('auth.landing');
    }

    public function showSignup() {
        return view('auth.signup');
    }

    public function signup(Request $request) {
        return redirect()->route('home.page');
    }

    public function showSignin() {
        return view('auth.signin');
    }

    public function signin(Request $request) {
        return redirect()->route('home.page');
    }
}
