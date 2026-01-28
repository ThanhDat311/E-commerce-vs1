<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSuggestion extends Model
{
    protected $fillable = [
        'product_id',
        'old_price',
        'new_price',
        'confidence',
        'reason',
        'status',
    ];

    protected $casts = [
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'confidence' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get confidence level as percentage
     */
    public function getConfidencePercentage()
    {
        return round($this->confidence * 100, 0);
    }

    /**
     * Get confidence level label
     */
    public function getConfidenceLabel()
    {
        $percent = $this->getConfidencePercentage();

        if ($percent >= 80) return 'Very High';
        if ($percent >= 60) return 'High';
        if ($percent >= 40) return 'Medium';
        if ($percent >= 20) return 'Low';
        return 'Very Low';
    }

    /**
     * Get price difference
     */
    public function getPriceDifference()
    {
        return $this->new_price - $this->old_price;
    }

    /**
     * Get price difference percentage
     */
    public function getPriceDifferencePercent()
    {
        if ($this->old_price == 0) return 0;
        return ($this->getPriceDifference() / $this->old_price) * 100;
    }
}
