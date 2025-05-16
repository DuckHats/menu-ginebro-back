<?php

namespace App\Services\Model;

use App\Http\Resources\OrderDetailsResource;
use App\Models\OrderDetail;
use App\Services\Generic\BaseService;

class OrderDetailsService extends BaseService
{
    public function __construct()
    {
        $this->model = new OrderDetail;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return OrderDetailsResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
