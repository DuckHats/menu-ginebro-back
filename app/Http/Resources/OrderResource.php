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
            'userData' => $this->whenLoaded('user', fn() => new UserResource($this->user)),
            
            'order_date' => $this->order_date,
            'allergies' => $this->allergies,
            'order_type_id' => $this->order_type_id,
            'orderType' => $this->whenLoaded('orderType', fn() => new OrderTypeResource($this->orderType)),
            'order_status_id' => $this->order_status_id,
            'orderStatus' => $this->whenLoaded('orderStatus', fn() => new OrderStatusResource($this->orderStatus)),

            'orderDetails' => $this->whenLoaded('orderDetails', fn() => OrderDetailResource::collection($this->orderDetails)),


            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
