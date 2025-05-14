<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Models\Order;

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
        return [
            'user',
            'orderDetails',
            'orderStatus',
            'orderType'
        ];
    }
}
