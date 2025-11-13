<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantLoginController extends Controller
{
    /**
     * Show the tenant login form.
     */
    public function showLoginForm()
    {
        return view('login'); // Blade file: resources/views/tenant/login.blade.php
    }

    /**
     * Handle the login request for tenants.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        // Attempt login using tenant guard
        if (Auth::guard('tenant')->attempt($credentials)) {
            // Regenerate session to prevent fixation
            $request->session()->regenerate();

            return redirect()->intended('/tenant/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or inactive account.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        return view('app');
    }
}
