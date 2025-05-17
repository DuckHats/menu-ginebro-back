<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuDayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'day_id' => $this->day_id,
            'day' => new DayResource($this->whenLoaded('day')),
            'menu' => new MenuResource($this->whenLoaded('menu')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
