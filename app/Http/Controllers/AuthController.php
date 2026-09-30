<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $r)
    {
        $cred = $r->validate(['username' => 'required', 'password' => 'required']);

        if (Auth::attempt($cred)) {
            $r->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->onlyInput('username');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('viewer.dashboard');
    }
}