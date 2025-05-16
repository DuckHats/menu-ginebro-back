<?php

namespace App\Services\Model;

use App\Http\Resources\DishResource;
use App\Models\Dish;
use App\Services\Generic\BaseService;

class DishService extends BaseService
{
    public function __construct()
    {
        $this->model = new Dish;
    }

    protected function getRelations(): array
    {
        return ['dishType'];
    }

    protected function resourceClass()
    {
        return DishResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}

