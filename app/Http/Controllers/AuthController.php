<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function admin()
    {
        return view('auth.admin');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'fname'     => 'required|string|max:25',
            'lname'     => 'required|string|max:25',
            'email'     => 'required|email|unique:users,username',
            'bday'      => 'required|date|before:today',
            'address'   => 'required|max:100',
            'password'  => 'required|min:8',
            'phone'     => 'required|numeric|digits:11|regex:/^09/',
            'gender'    => 'required|in:male,female',
        ]);

        $calculatedAge = $this->calculateAge($request->input('bday'));

        $user = User::create([
            'username'  => $request->email,
            'uname'     => $request->fname . ' ' . $request->lname,
            'password'  => Hash::make($request->password),
            'contactno' => $request->phone,
            'age'       => $calculatedAge,
            'gender'    => ($request->gender == 'male') ? 'M' : 'F',
            'address'   => $request->address,
        ]);

        return redirect()->route('home.page')->with('success', 'Account created!')->with('joined_date', date('F Y'))->with('user_age', $calculatedAge)->withInput();
    }

    public function showSignin()
    {
        return view('auth.signin');
        /*$isForgotPass = request()->routeIs('password.request');

        return view('auth.signin', compact('isForgotPass'));*/
    }

    public function signin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        $loginData = [
            'username' => $credentials['email'],
            'password' => $credentials['password'],
        ];

        

        if (Auth::attempt($loginData)) {
            $user = Auth::user();

            return redirect()->route('home.page')
                ->with('success', 'Welcome back!')
                ->with('user_email', $user->username)
                ->with('user_name', $user->uname)
                ->with('user_phone', $user->contactno)
                ->with('user_address', $user->address)
                ->with('user_age', $user->age)
                ->with('joined_date', date('F Y', strtotime($user->dateregistered)));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function payment()

    {
        return view('page.pay');
    }

    private function calculateAge($bday)
    {
        $birthDate = Carbon::parse($bday);
        $today = Carbon::now();

        $age = $birthDate->diff($today);
        return $age->y;
    }
}
