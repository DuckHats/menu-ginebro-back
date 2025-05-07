<?php

use App\Constants\RouteConstants;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

            Route::post(RouteConstants::VERIFY_PHONE, 'sendPhoneVerificationCode')->name('auth.sendPhoneVerificationCode')->middleware('auth:sanctum');
            Route::post(RouteConstants::VERIFY_PHONE_CONFIRM, 'verifyPhone')->name('auth.verifyPhone')->middleware('auth:sanctum');
        });

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

            Route::post(RouteConstants::USERS_AVATAR, 'updateAvatar')->name('users.avatar')->middleware('auth:sanctum');
            Route::post(RouteConstants::USERS_DISABLE, 'disableUser')->name('users.disable')->middleware('auth:sanctum');
            Route::post(RouteConstants::USERS_ENABLE, 'enableUser')->name('users.enable')->middleware('auth:sanctum');

            Route::post(RouteConstants::USERS_BULK, 'bulkUsers')->name('users.bulk')->middleware('auth:sanctum');
        });
    });
});