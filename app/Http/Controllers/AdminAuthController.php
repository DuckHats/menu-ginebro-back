<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    private $config;

    public function __construct()
    {
        $this->config = config('welcome');
    }

    public function showLoginForm()
    {
        if (session()->has('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login', [
            'appName' => $this->config['name'],
            'description' => $this->config['description'],
            'focusUrl' => $this->config['focus_url'],
            'documentation_url' => $this->config['documentation_url'],
            'primaryColor' => $this->config['primary_color'],
            'accentColor' => $this->config['accent_color'],
            'footerText' => $this->config['footer_text'],
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'password' => 'required',
        ]);

        if (
            $request->user === env('TELESCOPE_USER') &&
            $request->password === env('TELESCOPE_PASSWORD')
        ) {
            session(['admin_authenticated' => true]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['login' => 'Credencials incorrectes']);
    }

    public function logout()
    {
        session()->forget('admin_authenticated');

        return redirect()->route('admin.login');
    }
}
