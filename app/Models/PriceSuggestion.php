<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSuggestion extends Model
{
    protected $fillable = [
        'product_id',
        'old_price',
        'new_price',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
