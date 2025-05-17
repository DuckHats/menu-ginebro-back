<?php

namespace App\Services\Model;

use App\Http\Resources\OrderStatusResource;
use App\Models\OrderStatus;
use App\Services\Generic\BaseService;

class OrderStatusService extends BaseService
{
    public function __construct()
    {
        $this->model = new OrderStatus;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return OrderStatusResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
