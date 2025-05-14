<?php

namespace App\Services;

use App\Http\Resources\ImageResource;
use App\Models\Image;

class ImageService extends BaseService
{
    public function __construct()
    {
        $this->model = new Image;
    }

    protected function getRelations(): array
    {
        return [
            ''
        ];
    }

    protected function resourceClass()
    {
        return ImageResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [
            ''
        ];
    }
}
