<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Illuminate\Http\Request;
use App\Services\Generic\AuthService;
use App\Http\Controllers\DevAuthController;
use App\Constants\RouteConstants;
use App\Http\Controllers\GoogleAuthController;

Route::get('/', WelcomeController::class);

// Google Auth (session-based)
Route::prefix('api/v1')->group(function () {
    Route::get(RouteConstants::GOOGLE_AUTH, [GoogleAuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get(RouteConstants::GOOGLE_AUTH_CALLBACK, [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

if (env('APP_ENV') === 'local') {
    Route::get('/setup', [SetupController::class, 'setup']);

    // Dev-only auth visualizer (cookie/session flow)
    Route::prefix('dev/auth')->controller(DevAuthController::class)->group(function () {
        Route::get('/', 'show');
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/logout-all', 'logoutAll');
    });
}

// Rutes d'autenticació admin
Route::get('/admin', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Rutes protegides amb middleware AdminAuth
Route::middleware([AdminAuth::class])->group(function () {

    Route::get('/admin/dashboard', function () {
        $c = config('welcome');

        return view('admin.dashboard', [
            'appName' => $c['name'],
            'description' => $c['description'],
            'focusUrl' => $c['focus_url'],
            'documentation_url' => $c['documentation_url'],
            'primaryColor' => $c['primary_color'],
            'accentColor' => $c['accent_color'],
            'footerText' => $c['footer_text'],
        ]);
    })->name('admin.dashboard');

    Route::prefix('telescope')->group(function () {
        Route::get('/{any?}', function () {
            abort(403);
        })->where('any', '.*');
    });
});
