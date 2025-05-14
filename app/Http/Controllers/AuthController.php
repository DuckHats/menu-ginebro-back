<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\Generic\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        try {
            $data = $this->authService->register($request);

            return ApiResponse::success($data, 'Registered successfully', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'REGISTRATION_FAILED',
                'Error while creating user',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $this->authService->login($request);

            return ApiResponse::success($data, 'Login successful', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'LOGIN_FAILED',
                'Error during login',
                ['exception' => $e->getMessage()],
                ApiResponse::FORBIDDEN_STATUS
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request);

            return ApiResponse::success([], 'Logged out successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'LOGOUT_FAILED',
                'Error while logging out',
                ['exception' => $e->getMessage()],
                ApiResponse::NO_CONTENT_STATUS
            );
        }
    }

    public function sendResetCode(Request $request)
    {
        try {
            $this->authService->sendResetCode($request);

            return ApiResponse::success([], 'The reset code has been sent to your email.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'SEND_RESET_CODE_FAILED',
                'Error while sending reset code',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $this->authService->resetPassword($request);

            return ApiResponse::success([], 'Password reset successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'RESET_PASSWORD_FAILED',
                'Error while resetting password',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function sendEmailVerificationCode(Request $request)
    {
        try {
            $this->authService->sendEmailVerificationCode($request);

            return ApiResponse::success([], 'The verification code has been sent to your email.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'SEND_VERIFICATION_CODE_FAILED',
                'Error while sending verification code',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function verifyEmail(Request $request)
    {
        try {
            $this->authService->verifyEmail($request);

            return ApiResponse::success([], 'Email verified successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'VERIFY_EMAIL_FAILED',
                'Error while verifying email',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
