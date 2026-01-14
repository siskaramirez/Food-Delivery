<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function signup(Request $request)
    {
        $rules = [
            'fname'     => 'required|string|max:25',
            'lname'     => 'required|string|max:25',
            'email'     => 'required|email|unique:users,username|regex:/@gmail\.com$/',
            'bday'      => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString(),
            'address'   => 'required|max:100',
            'password'  => 'required|min:8',
            'phone'     => 'required|numeric|digits:11|regex:/^09/',
            'gender'    => 'required|in:male,female',
        ];

        $messages = [
            'bday.before_or_equal' => 'You must be at least 18 years old.',
        ];

        $request->validate($rules, $messages);

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

        /*
        Auth::login($user);
        $request->session()->regenerate();
        session(['user_role' => 'customer']); */

        return redirect()->route('signin.page')->with('success', 'Account created!')->with('user_email', $user->username);
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
            'email'    => 'required|email|regex:/@gmail\.com$/',
            'password' => 'required|min:8',
            'role'     => 'nullable|string'
        ]);

        $role = $request->input('role', 'customer');

        if ($role === 'admin') {

            $admin = DB::table('admins')->where('email', $credentials['email'])->first();

            if ($admin && Hash::check($credentials['password'], $admin->password)) {

                $request->session()->regenerate();
                session([
                    'user_role'  => 'admin',
                    'admin_id'   => $admin->adminid,
                    'user_name' => 'Administrator'
                ]);

                return redirect()->route('home.admin')->with('success', 'Admin Access Successfully');
            }
        } else {

            $loginData = [
                'username' => $credentials['email'],
                'password' => $credentials['password'],
            ];

            if (Auth::attempt($loginData)) {
                $user = Auth::user();
                $request->session()->regenerate();

                return redirect()->route('home.page')
                    ->with('success', 'Welcome back!')
                    ->with('user_email', $user->username)
                    ->with('user_name', $user->uname)
                    ->with('user_phone', $user->contactno)
                    ->with('user_address', $user->address)
                    ->with('user_age', $user->age)
                    ->with('joined_date', date('F Y', strtotime($user->dateregistered)));
            }
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    private function calculateAge($bday)
    {
        $birthDate = Carbon::parse($bday);
        $today = Carbon::now();

        $age = $birthDate->diff($today);
        return $age->y;
    }
}
