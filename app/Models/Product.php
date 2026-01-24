<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\VendorScope;
use App\Traits\Auditable;
use Laravel\Scout\Searchable;


class Product extends Model
{
    use HasFactory, Auditable, Searchable;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VendorScope());
    }

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'image_url',
        'is_new',
        'is_featured',
        'description',
    ];

    // --- THÊM ĐOẠN NÀY ---
    protected $casts = [
        'is_new' => 'boolean',
        'is_featured' => 'boolean',
    ];

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
}
