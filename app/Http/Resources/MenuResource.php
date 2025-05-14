<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'month' => $this->month,
            'week' => $this->week,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
