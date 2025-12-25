<?php

namespace App\Services\Model;

use App\Helpers\ApiResponse;
use App\Http\Resources\OrderResource;
use App\Jobs\OrderEndActions;
use App\Models\Order;
use App\Models\Configuration;
use App\Models\Transaction;
use App\Services\Generic\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService extends BaseService
{
    public function __construct()
    {
        $this->model = new Order;
    }

    protected function getRelations(): array
    {
        return [
            'orderDetail',
            'user',
            'orderType',
            'orderStatus',
        ];
    }

    protected function resourceClass()
    {
        return OrderResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return ['orderDetail'];
    }

    public function createOrder(Request $request, string $imageFieldName = 'image_url')
    {
        if (! $this->isAuthorized('create')) {
            return ApiResponse::error('UNAUTHORIZED', 'No tens permisos.', [], ApiResponse::FORBIDDEN_STATUS);
        }

        $validatedData = $this->validateRequest($request, 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $data = $validatedData['data'];
            $user = Auth()->user();

            // 1. Calculate and Validate Price
            $totalPrice = $this->calculateOrderPrice($data['order_type_id'], $data['has_tupper'] ?? false);

            // 2. Validate Balance
            $balanceCheck = $this->validateBalance($user, $totalPrice);
            if (! $balanceCheck['success']) {
                return ApiResponse::error('INSUFFICIENT_BALANCE', $balanceCheck['message'], [], ApiResponse::UNPROCESSABLE_ENTITY_STATUS);
            }

            DB::beginTransaction();

            // 3. Create Order
            $item = Order::create([
                'user_id' => $user->id,
                'order_date' => $data['order_date'],
                'order_type_id' => $data['order_type_id'],
                'order_status_id' => $data['order_status_id'],
                'allergies' => $this->getUserAllergies($user),
                'has_tupper' => $data['has_tupper'] ?? false,
                'total_price' => $totalPrice,
            ]);

            $item->orderDetail()->create([
                'option1' => $data['option1'] ?? null,
                'option2' => $data['option2'] ?? null,
                'option3' => $data['option3'] ?? null,
            ]);

            // 4. Deduct Balance
            $this->deductUserBalance($user, $totalPrice);

            // 5. Record Transaction
            Transaction::create([
                'user_id' => $user->id,
                'amount' => -$totalPrice,
                'type' => Transaction::TYPE_ORDER,
                'status' => 'completed',
                'description' => 'Pagament de comanda - ' . $data['order_date'],
                'internal_order_id' => $item->id,
            ]);

            // 6. Dispatch background actions (confirmation email, etc.)
            OrderEndActions::dispatch($user, $item);

            DB::commit();

            $item->load($this->getRelations());

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creating order', ['exception' => $e->getMessage()]);

            return ApiResponse::error('CREATE_FAILED', 'Error while creating item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    /**
     * Calculate the final order price based on configurations and type.
     */
    private function calculateOrderPrice(int $orderTypeId, bool $hasTupper): float
    {
        $configs = Configuration::whereIn('key', [
            'menu_price',
            'taper_price',
            'half_menu_first_price',
            'half_menu_second_price'
        ])->pluck('value', 'key');

        $menuPrice = floatval($configs['menu_price'] ?? 0);
        $taperPrice = floatval($configs['taper_price'] ?? 0);
        $halfFirstPrice = floatval($configs['half_menu_first_price'] ?? 0);
        $halfSecondPrice = floatval($configs['half_menu_second_price'] ?? 0);

        $orderType = \App\Models\OrderType::find($orderTypeId);
        $typeName = $orderType ? $orderType->name : '';

        $basePrice = 0;
        if (str_contains($typeName, 'Primer plat') && str_contains($typeName, 'Segon plat')) {
            $basePrice = $menuPrice;
        } elseif (str_contains($typeName, 'Primer plat')) {
            $basePrice = $halfFirstPrice;
        } elseif (str_contains($typeName, 'Segon plat')) {
            $basePrice = $halfSecondPrice;
        }

        $totalPrice = $basePrice + ($hasTupper ? $taperPrice : 0);

        return round($totalPrice, 2);
    }

    /**
     * Validate if the user has enough balance.
     */
    private function validateBalance($user, float $amount): array
    {
        if ($user->balance < $amount) {
            return [
                'success' => false,
                'message' => 'No tens prou saldo per fer aquesta comanda.',
            ];
        }

        return ['success' => true];
    }

    /**
     * Deduct the amount from the user balance.
     */
    private function deductUserBalance($user, float $amount): void
    {
        $user->balance -= $amount;
        $user->save();
    }

    public function updateOrderStatusBy_ID(Request $request, $id)
    {
        $item = $this->model->find($id);
        if (! $item) {
            return ApiResponse::error('NOT_FOUND', 'Item not found.', [], ApiResponse::NOT_FOUND_STATUS);
        }

        if (! $this->isAuthorized('updateStatus', $item)) {
            return ApiResponse::error('UNAUTHORIZED', 'No tens permisos.', [], ApiResponse::FORBIDDEN_STATUS);
        }

        $validatedData = $this->validateRequest($request, 'updateStatus', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $data = $validatedData['data'];

            DB::beginTransaction();

            $item->update([
                'order_status_id' => $data['order_status_id'],
            ]);

            DB::commit();

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error updating order status', ['exception' => $e->getMessage()]);

            return ApiResponse::error('UPDATE_FAILED', 'Error while updating item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function getByDate(Request $request, $date)
    {
        try {
            $query = Order::query()->with($this->getRelations());

            if ($date) {
                $query->where('order_date', $date);
            }

            // Apply Search
            if ($request->has('search') && ! empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                        ->orWhere('order_date', 'like', "%{$search}%")
                        ->orWhere('allergies', 'like', "%{$search}%")
                        ->orWhereHas('orderDetail', function ($dq) use ($search) {
                            $dq->where('option1', 'like', "%{$search}%")
                                ->orWhere('option2', 'like', "%{$search}%")
                                ->orWhere('option3', 'like', "%{$search}%");
                        });
                });
            }

            // Apply Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            switch ($sortBy) {
                case 'user':
                    $query->join('users', 'orders.user_id', '=', 'users.id')
                        ->orderBy('users.name', $sortOrder)
                        ->select('orders.*');
                    break;
                case 'type':
                    $query->join('order_types', 'orders.order_type_id', '=', 'order_types.id')
                        ->orderBy('order_types.name', $sortOrder)
                        ->select('orders.*');
                    break;
                case 'status':
                    $query->join('order_status', 'orders.order_status_id', '=', 'order_status.id')
                        ->orderBy('order_status.name', $sortOrder)
                        ->select('orders.*');
                    break;
                case 'plates':
                    $query->join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->orderBy('order_details.option1', $sortOrder)
                        ->select('orders.*');
                    break;
                case 'allergies':
                    $query->orderBy('allergies', $sortOrder);
                    break;
                case 'total_price':
                    $query->orderBy('total_price', $sortOrder);
                    break;
                case 'order_date':
                    $query->orderBy('order_date', $sortOrder);
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
            $items = $query->paginate($perPage);

            return $this->resourceClass()::collection($items)->additional([
                'status' => 'success',
                'message' => 'Items retrieved successfully.',
                'code' => ApiResponse::OK_STATUS,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error retrieving order by date', ['exception' => $e->getMessage()]);

            return ApiResponse::error('RETRIEVE_FAILED', 'Error while retrieving item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function getByUser(Request $request, $userId)
    {
        try {
            $query = Order::where('user_id', $userId)->with($this->getRelations());

            // Apply Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $allowedColumns = ['id', 'total_price', 'created_at'];

            if (in_array($sortBy, $allowedColumns)) {
                $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Apply Pagination
            $perPage = $request->get('per_page', 15);
            $items = $query->paginate($perPage);

            return $this->resourceClass()::collection($items)->additional([
                'status' => 'success',
                'message' => 'Items retrieved successfully.',
                'code' => ApiResponse::OK_STATUS,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error retrieving order by user', ['exception' => $e->getMessage()]);

            return ApiResponse::error('RETRIEVE_FAILED', 'Error while retrieving item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function checkDate(Request $request, $date)
    {
        try {
            $userId = Auth()->user()->id;
            $exists = Order::where('order_date', $date)
                ->where('user_id', $userId)
                ->exists();

            $data = [
                'available' => ! $exists,
            ];

            return ApiResponse::success(
                $data,
                'Check completed successfully.',
                ApiResponse::OK_STATUS
            );
        } catch (\Throwable $e) {
            Log::error('Error checking order date', ['exception' => $e->getMessage()]);

            return ApiResponse::error('RETRIEVE_FAILED', 'Error while retrieving item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    private function getUserAllergies($user)
    {
        $userAllergies = $user->allergies->pluck('name')->toArray();

        if ($user->custom_allergies) {
            $customAllergies = array_map('trim', explode(',', $user->custom_allergies));
            $userAllergies = array_merge($userAllergies, $customAllergies);
        }

        return implode(', ', array_unique($userAllergies));
    }
}
