<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order',
        'max_usage',
        'used_count',
        'per_user_limit',
        'starts_at',
        'expires_at',
        'is_active',
        'description',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_order' => 'decimal:2',
    ];

    /**
     * Relationship with UserCoupon
     */
    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class);
    }

    /**
     * Check if coupon is valid for use
     */
    public function isValid(): bool
    {
        // Check if active
        if (!$this->is_active) {
            return false;
        }

        // Check date range
        $now = now();
        if ($this->starts_at && $now < $this->starts_at) {
            return false; // Not started yet
        }
        if ($this->expires_at && $now > $this->expires_at) {
            return false; // Expired
        }

        // Check max usage
        if ($this->max_usage && $this->used_count >= $this->max_usage) {
            return false; // Exceeded max usage
        }

        return true;
    }

    /**
     * Check if user can use this coupon
     */
    public function canUserUse($userId): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->per_user_limit) {
            $userUsageCount = $this->userCoupons()
                ->where('user_id', $userId)
                ->whereNotNull('used_at')
                ->count();

            return $userUsageCount < $this->per_user_limit;
        }

        return true;
    }

    /**
     * Calculate discount amount for order
     */
    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->type === 'percent') {
            return ($orderTotal * $this->value) / 100;
        }

        // Fixed amount
        return min($this->value, $orderTotal); // Don't discount more than order total
    }

    /**
     * Mark coupon as used by incrementing counter
     */
    public function markAsUsed(): void
    {
        $this->increment('used_count');
    }
}
