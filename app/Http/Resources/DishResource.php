<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DishResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'dish_type_id' => $this->dish_type_id,
            'dish_type' => new DishTypeResource($this->whenLoaded('dishType')),
            'options' => $this->options,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
