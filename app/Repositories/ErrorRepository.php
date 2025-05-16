<?php

namespace App\Repositories;

use App\Models\Error;

class ErrorRepository
{
    public function createError($message, $code, $stackTrace, $userId, $sessionId, $occurredAt)
    {
        $error = new Error;
        $error->error_code = $code;
        $error->error_message = $message;
        $error->stack_trace = $stackTrace;
        $error->user_id = $userId;
        $error->session_id = $sessionId;
        $error->occurred_at = $occurredAt;
        $error->save();

        return $error;
    }
}
