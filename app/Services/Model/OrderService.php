<?php

namespace App\Services\Model;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Generic\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;


class OrderService extends BaseService
{
    public function __construct()
    {
        $this->model = new Order;
    }

    protected function getRelations(): array
    {
        return [
            'orderDetails',
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
        return ['orderDetails'];
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

            DB::beginTransaction();

            $item = Order::create([
                'user_id' => $data['user_id'],
                'order_date' => $data['order_date'],
                'order_type_id' => $data['order_type_id'],
                'order_status_id' => $data['order_status_id'],
                'allergies' => $data['allergies'] ?? null,
            ]);

            if (isset($data['dish_ids']) && is_array($data['dish_ids'])) {
                foreach ($data['dish_ids'] as $dishId) {
                    $item->orderDetails()->create(['dish_id' => $dishId]);
                }
            }

            DB::commit();

            $item->load($this->getRelations());

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creating order', ['exception' => $e->getMessage()]);

            return ApiResponse::error('CREATE_FAILED', 'Error while creating item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
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
}
