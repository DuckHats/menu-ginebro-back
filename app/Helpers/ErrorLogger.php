<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ErrorLogger
{
    /**
     * Log an error to the database.
     */
    public static function log(string $errorCode, string $errorMessage, ?string $stackTrace = null, ?int $userId = null): void
    {
        Log::error('Error: ', [
            'original_error_code' => $errorCode,
            'original_error_message' => $errorMessage,
        ]);
    }
}
