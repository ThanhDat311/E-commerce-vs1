<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sale_price',
        'quantity_limit',
        'quantity_sold',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'sale_price' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if flash sale is currently active
     */
    public function isActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        // Check if within time window
        if ($now < $this->starts_at || $now > $this->ends_at) {
            return false;
        }

        // Check quantity availability
        if ($this->quantity_limit && $this->quantity_sold >= $this->quantity_limit) {
            return false;
        }

        return true;
    }

    /**
     * Get time remaining in seconds
     */
    public function getTimeRemainingSeconds(): int
    {
        return max(0, $this->ends_at->diffInSeconds(now()));
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentage(): float
    {
        $original = $this->product->price;
        $sale = $this->sale_price;

        if ($original > 0) {
            return (($original - $sale) / $original) * 100;
        }

        return 0;
    }

    /**
     * Increment quantity sold
     */
    public function incrementSold($quantity = 1): void
    {
        $this->increment('quantity_sold', $quantity);
    }
}
