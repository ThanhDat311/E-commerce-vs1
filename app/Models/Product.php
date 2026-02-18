<?php

namespace App\Models;

use App\Models\Scopes\VendorScope;
use App\Traits\Auditable;
use App\Traits\HasFlashSalePrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Auditable, HasFactory, HasFlashSalePrice, Searchable, SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VendorScope);
    }

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'slug', // Add slug
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'image_url',
        'is_new',
        'is_featured',
        'description',
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Illuminate\Support\Str::slug($product->name) . '-' . \Illuminate\Support\Str::random(6);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = \Illuminate\Support\Str::slug($product->name) . '-' . $product->id;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function averageRating()
    {
        return round($this->ratings()->avg('rating'), 1);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function priceSuggestions()
    {
        return $this->hasMany(PriceSuggestion::class);
    }

    /**
     * Get the indexable data array for the model.
     *
     * Defines which fields Scout should index for full-text search
     * (name, description, price, category_id)
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'vendor_id' => $this->vendor_id,
            'sku' => $this->sku,
        ];
    }

    public function getImageUrlAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset($value);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
