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
     */
    public function getEffectivePriceAttribute(): float
    {
        $flashSaleService = app(FlashSaleService::class);

        return $flashSaleService->getEffectivePrice($this);
    }

    /**
     * Get the sale price if active flash sale exists
     */
    public function getSalePriceAttribute(): ?float
    {
        $flashSaleService = app(FlashSaleService::class);

        return $flashSaleService->getSalePrice($this);
    }

    /**
     * Get discount percentage for current flash sale
     */
    public function getDiscountPercentageAttribute(): ?float
    {
        $flashSaleService = app(FlashSaleService::class);

        return $flashSaleService->getDiscountPercentage($this);
    }

    /**
     * Get time remaining for flash sale in seconds
     */
    public function getTimeRemainingAttribute(): ?int
    {
        $flashSaleService = app(FlashSaleService::class);

        return $flashSaleService->getTimeRemaining($this);
    }

    /**
     * Get formatted countdown (e.g., "2h 15m 30s")
     */
    public function getFormattedCountdownAttribute(): ?string
    {
        $flashSaleService = app(FlashSaleService::class);

        return $flashSaleService->getFormattedCountdown($this);
    }

    /**
     * Check if product is currently on flash sale
     */
    public function getIsOnSaleAttribute(): bool
    {
        $flashSaleService = app(FlashSaleService::class);

        return $flashSaleService->isOnSale($this->id);
    }
}
