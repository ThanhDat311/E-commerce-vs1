<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'order_number' => $this->whenLoaded('order', fn () => $this->order?->order_number),
            'order_total' => $this->order_total,
            'commission_rate' => $this->commission_rate,
            'commission_amount' => $this->commission_amount,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
