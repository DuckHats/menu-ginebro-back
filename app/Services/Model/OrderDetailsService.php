<?php

namespace App\Services;

use App\Http\Resources\OrderDetailsResource;
use App\Models\OrderDetail;

class OrderDetailsService extends BaseService
{
    public function __construct()
    {
        $this->model = new OrderDetail;
    }

    protected function getRelations(): array
    {
        return [
            ''
        ];
    }

    protected function resourceClass()
    {
        return OrderDetailsResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [
            ''
        ];
    }
}
