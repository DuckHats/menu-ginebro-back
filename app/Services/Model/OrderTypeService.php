<?php

namespace App\Services\Model;

use App\Http\Resources\OrderTypeResource;
use App\Models\OrderType;
use App\Services\Generic\BaseService;

class OrderTypeService extends BaseService
{
    public function __construct()
    {
        $this->model = new OrderType;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return OrderTypeResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
