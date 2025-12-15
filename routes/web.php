<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Illuminate\Http\Request;

Route::get('/', WelcomeController::class);

Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

if (env('APP_ENV') === 'local') {
    Route::get('/setup', [SetupController::class, 'setup']);
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
