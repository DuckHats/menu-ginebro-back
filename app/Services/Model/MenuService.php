<?php

namespace App\Services\Model;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Services\Generic\BaseService;

class MenuService extends BaseService
{
    public function __construct()
    {
        $this->model = new Menu;
    }

    protected function getRelations(): array
    {
        return ['dishes'];
    }

    protected function resourceClass()
    {
        return MenuResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
