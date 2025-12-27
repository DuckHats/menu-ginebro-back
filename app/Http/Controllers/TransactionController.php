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

        // Apply Search
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // Apply Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'user':
                $query->join('users', 'transactions.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sortOrder)
                    ->select('transactions.*');
                break;
            case 'amount':
                $query->orderBy('amount', $sortOrder);
                break;
            case 'status':
                $query->orderBy('status', $sortOrder);
                break;
            case 'description':
                $query->orderBy('description', $sortOrder);
                break;
            case 'id':
                $query->orderBy('id', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
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
