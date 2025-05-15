<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_date' => $this->order_date,
            'allergies' => $this->allergies,
            'order_type_id' => $this->order_type_id,
            'order_status_id' => $this->order_status_id,

            'orderUser' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            // 'orderDetails' => $this->whenLoaded('orderDetails', fn () => OrderDetailResource::collection($this->orderDetails ?? collect())),
            'orderStatus' => $this->whenLoaded('orderStatus', fn () => OrderStatusResource::collection($this->orderStatus ?? collect())),
            'orderType' => $this->whenLoaded('orderType', fn () => OrderTypeResource::collection($this->orderType ?? collect())),
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
