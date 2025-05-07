<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (session()->has('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'password' => 'required',
        ]);

        if ($request->user === env('TELESCOPE_USER') && $request->password === env('TELESCOPE_PASSWORD')) {
            session(['admin_authenticated' => true]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function logout()
    {
        session()->forget('admin_authenticated');

        return redirect()->route('admin.login');
    }
}
