<?php

namespace App\Helpers;

use App\Models\Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorLogger
{
    /**
     * Log an error to the database.
     */
    public static function log(string $errorCode, string $errorMessage, ?string $stackTrace = null, ?int $userId = null): void
    {
        try {
            $userId = $userId ?? (Auth::check() ? Auth::id() : null);

            Error::create([
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
                'stack_trace' => $stackTrace,
                'user_id' => $userId,
                'occurred_at' => now(),
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to log error: '.$e->getMessage(), [
                'original_error_code' => $errorCode,
                'original_error_message' => $errorMessage,
            ]);
        }
    }
}
