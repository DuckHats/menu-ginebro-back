<?php

namespace App\Http\Controllers;

use App\Services\Generic\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;

class DevAuthController extends Controller
{
    public function show(Request $request): View
    {
        $user = auth()->user();
        $sessionName = config('session.cookie');
        $cookies = $request->cookies->all();

        return view('dev.auth', compact('user', 'sessionName', 'cookies'));
    }

    public function login(Request $request, AuthService $authService): RedirectResponse
    {
        try {
            $authService->login($request);
            return redirect('/dev/auth')->with('success', 'Logged in');
        } catch (\Throwable $e) {
            return redirect('/dev/auth')->with('error', $e->getMessage());
        }
    }

    public function register(Request $request, AuthService $authService): RedirectResponse
    {
        try {
            $authService->register($request);
            return redirect('/dev/auth')->with('success', 'Registered and logged in');
        } catch (\Throwable $e) {
            return redirect('/dev/auth')->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request, AuthService $authService): RedirectResponse
    {
        try {
            $authService->logout($request);
        } catch (\Throwable $e) {
            // ignore
        }
        return redirect('/dev/auth')
            ->with('success', 'Logged out')
            ->withCookie(Cookie::forget(config('session.cookie')))
            ->withCookie(Cookie::forget('XSRF-TOKEN'));
    }

    public function logoutAll(Request $request, AuthService $authService): RedirectResponse
    {
        try {
            $authService->logoutAllSessions($request);
        } catch (\Throwable $e) {
            // ignore
        }
        return redirect('/dev/auth')
            ->with('success', 'All sessions logged out')
            ->withCookie(Cookie::forget(config('session.cookie')))
            ->withCookie(Cookie::forget('XSRF-TOKEN'));
    }
}
