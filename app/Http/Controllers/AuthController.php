<?php

namespace App\Http\Controllers;

use App\Constants\ErrorCodes;
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

            return ApiResponse::success($data, config('messages.auth.register_success'), ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::REGISTRATION_FAILED,
                config('messages.errors.registration_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $this->authService->login($request);

            return ApiResponse::success($data, config('messages.auth.login_success'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::LOGIN_FAILED,
                config('messages.errors.login_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::FORBIDDEN_STATUS
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request);

            return ApiResponse::success([], config('messages.auth.logout_success'));
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::LOGOUT_FAILED,
                config('messages.errors.logout_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::NO_CONTENT_STATUS
            );
        }
    }

    public function logoutAllSessions(Request $request)
    {
        try {
            $this->authService->logoutAllSessions($request);

            // Reuse same success message
            return ApiResponse::success([], config('messages.auth.logout_success'));
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::LOGOUT_FAILED,
                config('messages.errors.logout_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::NO_CONTENT_STATUS
            );
        }
    }

    public function sendResetCode(Request $request)
    {
        try {
            $this->authService->sendResetCode($request);

            return ApiResponse::success([], config('messages.auth.reset_code_sent'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::SEND_RESET_CODE_FAILED,
                config('messages.errors.send_reset_code_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $this->authService->resetPassword($request);

            return ApiResponse::success([], config('messages.auth.password_reset_success'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::RESET_PASSWORD_FAILED,
                config('messages.errors.reset_password_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function sendEmailVerificationCode(Request $request)
    {
        try {
            $this->authService->sendEmailVerificationCode($request);

            return ApiResponse::success([], config('messages.auth.verification_code_sent'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::SEND_VERIFICATION_CODE_FAILED,
                config('messages.errors.send_verification_code_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function verifyEmail(Request $request)
    {
        try {
            $this->authService->verifyEmail($request);

            return ApiResponse::success([], config('messages.auth.email_verified_success'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::VERIFY_EMAIL_FAILED,
                config('messages.errors.verify_email_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function sendRegisterCode(Request $request)
    {
        try {
            $this->authService->sendRegisterCode($request);

            return ApiResponse::success([], config('messages.auth.register_code_sent'));
        } catch (\Throwable $e) {
            return ApiResponse::error(ErrorCodes::SEND_REGISTER_CODE_FAILED, config('messages.errors.send_register_code_failed'), ['exception' => $e->getMessage()]);
        }
    }

    public function completeRegister(Request $request)
    {
        try {
            $data = $this->authService->completeRegister($request);

            return ApiResponse::success($data, config('messages.auth.register_complete_success'), ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(ErrorCodes::COMPLETE_REGISTER_FAILED, config('messages.errors.complete_register_failed'), ['exception' => $e->getMessage()]);
        }
    }
}
