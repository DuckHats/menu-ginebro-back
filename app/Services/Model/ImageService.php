<?php

namespace App\Services\Model;

use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Services\Generic\BaseService;

class ImageService extends BaseService
{
    public function __construct()
    {
        $this->model = new Image;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return ImageResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
