<?php

namespace App\Constants;

class ErrorCodes
{
    // Authentication error codes
    public const REGISTRATION_FAILED = 'REGISTRATION_FAILED';
    public const LOGIN_FAILED = 'LOGIN_FAILED';
    public const LOGOUT_FAILED = 'LOGOUT_FAILED';
    public const SEND_RESET_CODE_FAILED = 'SEND_RESET_CODE_FAILED';
    public const RESET_PASSWORD_FAILED = 'RESET_PASSWORD_FAILED';
    public const SEND_VERIFICATION_CODE_FAILED = 'SEND_VERIFICATION_CODE_FAILED';
    public const VERIFY_EMAIL_FAILED = 'VERIFY_EMAIL_FAILED';
    public const SEND_REGISTER_CODE_FAILED = 'SEND_REGISTER_CODE_FAILED';
    public const COMPLETE_REGISTER_FAILED = 'COMPLETE_REGISTER_FAILED';

    // Granular Authentication error codes
    public const INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';
    public const ACCOUNT_INACTIVE = 'ACCOUNT_INACTIVE';
    public const EMAIL_ALREADY_REGISTERED = 'EMAIL_ALREADY_REGISTERED';
    public const INVALID_VERIFICATION_CODE = 'INVALID_VERIFICATION_CODE';
    public const USER_ALREADY_EXISTS = 'USER_ALREADY_EXISTS';
    public const INVALID_OR_EXPIRED_CODE = 'INVALID_OR_EXPIRED_CODE';

    // Generic CRUD error codes
    public const VALIDATION_ERROR = 'VALIDATION_ERROR';
    public const FETCH_FAILED = 'FETCH_FAILED';
    public const CREATE_FAILED = 'CREATE_FAILED';
    public const UPDATE_FAILED = 'UPDATE_FAILED';
    public const DELETE_FAILED = 'DELETE_FAILED';
    public const NOT_FOUND = 'NOT_FOUND';

    // Permission error codes
    public const UNAUTHORIZED = 'UNAUTHORIZED';
    public const FORBIDDEN = 'FORBIDDEN';

    // Function error codes
    public const FUNCTION_FAILED = 'FUNCTION_FAILED';
}
