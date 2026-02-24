<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_count',
        'apply_scope',
        'vendor_id',
        'priority',
        'status',
        'created_by',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'discount_value' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_count' => 'integer',
            'priority' => 'integer',
        ];
    }

    /**
     * Boot – auto generate slug if not provided.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Deal $deal) {
            if (empty($deal->slug)) {
                $deal->slug = Str::slug($deal->name);
            }
        });
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function products()
    {
        return $this->belongsToMany(Product::class, 'deal_products')
            ->withPivot('special_price')
            ->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'deal_categories')
            ->withTimestamps();
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // -------------------------------------------------------------------------
    // Helper Methods
    // -------------------------------------------------------------------------

    /**
     * Check if the deal is currently active (status + time range + usage limit).
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if the deal is expired by time.
     */
    public function isExpired(): bool
    {
        return now()->gt($this->end_date);
    }
}
