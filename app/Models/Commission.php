<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'vendor_id',
        'order_total',
        'commission_rate',
        'commission_amount',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'order_total' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Net payout amount = order_total - commission_amount
     */
    public function getNetPayoutAttribute(): float
    {
        return (float) $this->order_total - (float) $this->commission_amount;
    }
}
