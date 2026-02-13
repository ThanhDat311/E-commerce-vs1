<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'rate',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get the effective commission rate for a vendor.
     * Falls back to the global rate if no vendor-specific rate exists.
     */
    public static function getRateForVendor(?int $vendorId = null): float
    {
        if ($vendorId) {
            $vendorSetting = static::where('vendor_id', $vendorId)
                ->where('is_active', true)
                ->first();

            if ($vendorSetting) {
                return (float) $vendorSetting->rate;
            }
        }

        $global = static::whereNull('vendor_id')
            ->where('is_active', true)
            ->first();

        return $global ? (float) $global->rate : 8.50;
    }
}
