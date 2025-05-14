<?php

use App\Constants\RouteConstants;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DishController;
use Illuminate\Http\Request;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('throttle:api')->group(function () {

    // Public routes
    Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post(RouteConstants::REGISTER, 'register')->name('auth.register');
            Route::post(RouteConstants::LOGIN, 'login')->name('auth.login');

            Route::post(RouteConstants::LOGOUT, 'logout')->name('auth.logout')->middleware('auth:sanctum');
            Route::post(RouteConstants::LOGOUT_ALL_SESSIONS, 'logoutAllSessions')->name('auth.logoutAll')->middleware('auth:sanctum');
            Route::post(RouteConstants::FORGOT_PASSWORD, 'sendResetCode')->name('auth.sendResetCode');
            Route::post(RouteConstants::RESET_PASSWORD, 'resetPassword')->name('auth.resetPassword');

            Route::post(RouteConstants::VERIFY_EMAIL, 'sendEmailVerificationCode')->name('auth.sendEmailVerificationCode')->middleware('auth:sanctum');
            Route::post(RouteConstants::VERIFY_EMAIL_CONFIRM, 'verifyEmail')->name('auth.verifyEmail')->middleware('auth:sanctum');

        });

        // User routes
        Route::controller(UserController::class)->group(function () {
            Route::get(RouteConstants::USERS, 'index')->name('users.index')->middleware('auth:sanctum');
            Route::get(RouteConstants::USERS_ME, 'me')->name('users.me')->middleware('auth:sanctum');
            Route::get(RouteConstants::USERS_EXPORT, 'export')->name('users.export')->middleware('auth:sanctum');

            Route::get(RouteConstants::USERS_ADMIN_CHECK, 'is_admin')->name('users.adminCheck')->middleware('auth:sanctum');
            Route::get(RouteConstants::USERS_DETAIL, 'show')->name('users.show')->middleware('auth:sanctum');

            Route::post(RouteConstants::USERS_CREATE, 'store')->name('users.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::USERS_UPDATE, 'update')->name('users.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::USERS_PATCH, 'patch')->name('users.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::USERS_DESTROY, 'destroy')->name('users.destroy')->middleware('auth:sanctum');

            Route::post(RouteConstants::USERS_DISABLE, 'disableUser')->name('users.disable')->middleware('auth:sanctum');
            Route::post(RouteConstants::USERS_ENABLE, 'enableUser')->name('users.enable')->middleware('auth:sanctum');
            
        });

        // Menu routes
        Route::controller(MenuController::class)->group(function () {
            Route::get(RouteConstants::MENUS, 'index')->name('menus.index')->middleware('auth:sanctum');
            Route::get(RouteConstants::MENUS_EXPORT, 'export')->name('menus.export')->middleware('auth:sanctum');

            Route::get(RouteConstants::MENUS_DETAIL, 'show')->name('menus.show')->middleware('auth:sanctum');

            Route::post(RouteConstants::MENUS_CREATE, 'store')->name('menus.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::MENUS_UPDATE, 'update')->name('menus.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::MENUS_PATCH, 'patch')->name('menus.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::MENUS_DESTROY, 'destroy')->name('menus.destroy')->middleware('auth:sanctum');

            Route::post(RouteConstants::MENUS_DISABLE, 'disableMenu')->name('menus.disable')->middleware('auth:sanctum');
            Route::post(RouteConstants::MENUS_ENABLE, 'enableMenu')->name('menus.enable')->middleware('auth:sanctum');
        });

        // Dish routes
        Route::controller(DishController::class)->group(function () {
            Route::get(RouteConstants::DISHES, 'index')->name('dishes.index')->middleware('auth:sanctum');
            Route::get(RouteConstants::DISHES_EXPORT, 'export')->name('dishes.export')->middleware('auth:sanctum');

            Route::get(RouteConstants::DISHES_DETAIL, 'show')->name('dishes.show')->middleware('auth:sanctum');

            Route::post(RouteConstants::DISHES_CREATE, 'store')->name('dishes.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::DISHES_UPDATE, 'update')->name('dishes.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::DISHES_PATCH, 'patch')->name('dishes.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::DISHES_DESTROY, 'destroy')->name('dishes.destroy')->middleware('auth:sanctum');
        });
    });
});
