<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'image_path'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImagePathAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        // Old format: img/products/gallery/... → serve from public/img/
        if (str_starts_with($value, 'img/')) {
            return asset($value);
        }

        // New format: products/gallery/... → serve from storage link
        return asset('storage/'.$value);
    }
}
