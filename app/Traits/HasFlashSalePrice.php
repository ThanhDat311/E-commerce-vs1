<?php

namespace App\Traits;

use App\Services\FlashSaleService;

/**
 * HasFlashSalePrice
 * 
 * Adds automatic flash sale price calculation to Product model
 * Returns sale_price if within flash sale window, otherwise regular price
 */
trait HasFlashSalePrice
{
    /**
     * Get the effective price (sale price if active, else regular price)
     * 
     * @return float
     */
    public function getEffectivePriceAttribute(): float
    {
        $flashSaleService = app(FlashSaleService::class);
        return $flashSaleService->getEffectivePrice($this);
    }

    /**
     * Get the sale price if active flash sale exists
     * 
     * @return float|null
     */
    public function getSalePriceAttribute(): ?float
    {
        $flashSaleService = app(FlashSaleService::class);
        return $flashSaleService->getSalePrice($this);
    }

    /**
     * Get discount percentage for current flash sale
     * 
     * @return float|null
     */
    public function getDiscountPercentageAttribute(): ?float
    {
        $flashSaleService = app(FlashSaleService::class);
        return $flashSaleService->getDiscountPercentage($this);
    }

    /**
     * Get time remaining for flash sale in seconds
     * 
     * @return int|null
     */
    public function getTimeRemainingAttribute(): ?int
    {
        $flashSaleService = app(FlashSaleService::class);
        return $flashSaleService->getTimeRemaining($this);
    }

    /**
     * Get formatted countdown (e.g., "2h 15m 30s")
     * 
     * @return string|null
     */
    public function getFormattedCountdownAttribute(): ?string
    {
        $flashSaleService = app(FlashSaleService::class);
        return $flashSaleService->getFormattedCountdown($this);
    }

    /**
     * Check if product is currently on flash sale
     * 
     * @return bool
     */
    public function getIsOnSaleAttribute(): bool
    {
        $flashSaleService = app(FlashSaleService::class);
        return $flashSaleService->isOnSale($this->id);
    }
}
