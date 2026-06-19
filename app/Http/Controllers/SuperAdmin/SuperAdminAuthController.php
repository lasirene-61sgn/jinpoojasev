<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if(Auth::guard('superadmin')->check()){
            return redirect()->route('superadmin.dashboard');
        }

        return view('superadmin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if(Auth::guard('superadmin')->attempt($credentials, $request->remember)){
            $request->session()->regenerate();
            return redirect()->intended(route('superadmin.dashboard'))->with('success', 'Login Success');
        }
        return back()->withErrors([
            'email' => 'Provided Credentials are wrong check again',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('superadmin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('superadmin.login')->with('success', 'logout');
    }
}
