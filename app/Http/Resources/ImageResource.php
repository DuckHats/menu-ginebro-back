<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'month' => $this->month,
            'year' => $this->year,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
