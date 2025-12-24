<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Get transactions for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $transactions = Transaction::where('user_id', $user->id)
            ->with(['internalOrder'])
            ->orderBy('created_at', 'desc')
            ->get();

        return ApiResponse::success($transactions, 'Transactions retrieved successfully.');
    }

    /**
     * Get all transactions (Admin only).
     */
    public function adminIndex(Request $request)
    {
        if (!$request->user()->is_admin) {
            return ApiResponse::error('UNAUTHORIZED', 'No tens permisos.', [], ApiResponse::FORBIDDEN_STATUS);
        }

        $transactions = Transaction::with(['user', 'internalOrder'])
            ->orderBy('created_at', 'desc')
            ->get();

        return ApiResponse::success($transactions, 'All transactions retrieved successfully.');
    }
}
