<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\OrderResource;
use App\Jobs\BulkOrderCreationJob;
use App\Models\Order;
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
            'user',
            'orderDetails',
            'orderStatus',
            'orderType'
        ];
    }

    protected function resourceClass()
    {
        return OrderResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [''];
    }
}
