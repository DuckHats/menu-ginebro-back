<?php

namespace App\Helpers;

class ApiResponse
{
    const OK_STATUS = 200;

    const CREATED_STATUS = 201;

    const ACCEPTED_STATUS = 202;

    const NO_CONTENT_STATUS = 204;

    const INVALID_PARAMETERS_STATUS = 400;

    const UNAUTHORIZED_STATUS = 401;

    const FORBIDDEN_STATUS = 403;

    const NOT_FOUND_STATUS = 404;

    const METHOD_NOT_ALLOWED_STATUS = 405;

    const UNPROCESSABLE_ENTITY_STATUS = 422;

    const INTERNAL_SERVER_ERROR_STATUS = 500;

    const TOO_MANY_REQUESTS_STATUS = 429;

    public static function success($data = [], $message = 'Request successful', $status = self::OK_STATUS)
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    public static function warning($data = [], $message = 'Warning!', $status = self::INVALID_PARAMETERS_STATUS)
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    public static function error($code, $message, $details = [], $status = self::INVALID_PARAMETERS_STATUS)
    {
        return response()->json([
            'status' => $status,
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details,
            ],
        ], $status);
    }
}
