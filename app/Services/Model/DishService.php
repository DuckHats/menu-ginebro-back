<?php

namespace App\Services;

use App\Http\Resources\DishResource;
use App\Models\Dish;

class DishService extends BaseService
{
    public function __construct()
    {
        $this->model = new Dish;
    }

    protected function getRelations(): array
    {
        return [
            ''
        ];
    }

    protected function resourceClass()
    {
        return DishResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [
            ''
        ];
    }
}
