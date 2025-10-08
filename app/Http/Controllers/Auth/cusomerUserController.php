<?php

namespace App\Http\Controllers\Auth;

use App\Models\CustomerUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class cusomerUserController extends Controller
{
    public function showRegistrationForm()
    {
        return view('front.auth.register');
    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'address' => 'required',
            'phone' => 'required',
            'birthDate' => 'required',
        ]);
        CustomerUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'birthDate' => $request->birthDate,
        ]);

        return redirect('/customerLoginPage')->with('success', 'Registration successful! Please log in.');
    }

    public function showLoginForm()
    {
        return view('front.auth.login');
    }

    public function login(Request $request)
    {
        $logIN = CustomerUser::where(['email' => $request->email])->get();

        dd($request, $logIN);

        if ($logIN) {
            session()->put('cusmeruser', $logIN->name);
            return redirect()->intended('/');
        }

        return redirect('/login')->with('error', 'Invalid credentials. Please try again.');
    }
}
