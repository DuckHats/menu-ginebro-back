<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'option1' => $this->option1,
            'option2' => $this->option2,
            'option3' => $this->option3,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
