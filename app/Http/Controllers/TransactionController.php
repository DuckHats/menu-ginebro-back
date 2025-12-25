<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\TransactionResource;
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
        $query = Transaction::where('user_id', $user->id)->with(['internalOrder']);

        // Apply Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedColumns = ['id', 'amount', 'type', 'status', 'created_at'];

        if (in_array($sortBy, $allowedColumns)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Apply Pagination
        $perPage = $request->get('per_page', 15);
        $transactions = $query->paginate($perPage);

        return TransactionResource::collection($transactions)->additional([
            'status' => 'success',
            'message' => 'Transactions retrieved successfully.',
            'code' => ApiResponse::OK_STATUS,
        ]);
    }

    /**
     * Get all transactions (Admin only).
     */
    public function adminIndex(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return ApiResponse::error('UNAUTHORIZED', 'No tens permisos.', [], ApiResponse::FORBIDDEN_STATUS);
        }

        $query = Transaction::with(['user', 'internalOrder']);

        // Apply Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedColumns = ['id', 'user_id', 'amount', 'type', 'status', 'created_at'];

        if (in_array($sortBy, $allowedColumns)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Apply Pagination
        $perPage = $request->get('per_page', 15);
        $transactions = $query->paginate($perPage);

        return \App\Http\Resources\TransactionResource::collection($transactions)->additional([
            'status' => 'success',
            'message' => 'All transactions retrieved successfully.',
            'code' => ApiResponse::OK_STATUS,
        ]);
    }
}
