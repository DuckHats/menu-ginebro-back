<?php

namespace App\Services;

use App\Http\Resources\MenuDayResource;
use App\Models\MenuDay;

class MenuDayService extends BaseService
{
    public function __construct()
    {
        $this->model = new MenuDay;
    }

    protected function getRelations(): array
    {
        return [
            ''
        ];
    }

    protected function resourceClass()
    {
        return MenuDayResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [
            ''
        ];
    }
}
