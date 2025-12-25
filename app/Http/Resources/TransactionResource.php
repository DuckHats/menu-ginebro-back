<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'amount' => (float) $this->amount,
            'type' => $this->type,
            'description' => $this->description,
            'status' => $this->status,
            'internal_order_id' => $this->internal_order_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Loaded relations
            'user' => $this->whenLoaded('user', fn() => new UserResource($this->user)),
            'internalOrder' => $this->whenLoaded('internalOrder', fn() => new OrderResource($this->internalOrder)),
        ];
    }
}
