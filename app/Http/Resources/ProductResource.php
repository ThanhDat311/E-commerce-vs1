<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'stock_quantity' => $this->stock_quantity,
            'image_url' => $this->image_url ? asset($this->image_url) : null,
            'is_new' => (bool) $this->is_new,
            'is_featured' => (bool) $this->is_featured,
            'average_rating' => $this->average_rating,
            'category' => $this->whenLoaded('category', fn() => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ]),
            'vendor' => $this->whenLoaded('vendor', fn() => [
                'id' => $this->vendor->id,
                'name' => $this->vendor->name,
            ]),
            'gallery' => $this->whenLoaded('images', fn() => $this->images->map(fn($img) => [
                'id' => $img->id,
                'url' => asset($img->image_path),
            ])),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
