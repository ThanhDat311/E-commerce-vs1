<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    protected $fillable = [
        'user_id',
        'cardholder_name',
        'card_brand',
        'last_four',
        'expiry_month',
        'expiry_year',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'expiry_month' => 'integer',
            'expiry_year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMaskedNumberAttribute(): string
    {
        return '**** **** **** '.$this->last_four;
    }

    public function getIsExpiredAttribute(): bool
    {
        $now = now();

        return $this->expiry_year < $now->year
            || ($this->expiry_year === $now->year && $this->expiry_month < $now->month);
    }
}
