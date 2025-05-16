<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SetupController;
use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

if (env('APP_ENV') === 'local') {
    Route::get('/setup', [SetupController::class, 'setup']);
}

Route::get('/admin', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::middleware([AdminAuth::class])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::prefix('telescope')->group(function () {
        Route::get('/{any?}', function () {
            abort(403);
        })->where('any', '.*');
    });
});

Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
