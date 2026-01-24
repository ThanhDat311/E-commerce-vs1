<?php

namespace App\Services;

use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

/**
 * FlashSaleService
 * 
 * Handles flash sale retrieval, pricing, and inventory management
 * with real-time active status checking
 */
class FlashSaleService
{
    /**
     * Get active flash sale for a product
     * 
     * @param int $productId
     * 
     * @return FlashSale|null
     */
    public function getActiveFlashSale(int $productId): ?FlashSale
    {
        $sale = FlashSale::where('product_id', $productId)
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->first();

        if ($sale) {
            // Check quantity limit if set
            if ($sale->quantity_limit && $sale->quantity_sold >= $sale->quantity_limit) {
                return null; // Sale is sold out
            }
        }

        return $sale;
    }

    /**
     * Get sale price for a product if active flash sale exists
     * Falls back to regular product price if no sale
     * 
     * @param Product $product
     * 
     * @return decimal|null Sale price, or null if product has no active sale
     */
    public function getSalePrice(Product $product)
    {
        $sale = $this->getActiveFlashSale($product->id);

        return $sale ? $sale->sale_price : null;
    }

    /**
     * Get effective price for a product (sale price or regular price)
     * 
     * @param Product $product
     * 
     * @return float Effective price (sale if active, else regular)
     */
    public function getEffectivePrice(Product $product): float
    {
        $salePrice = $this->getSalePrice($product);

        return $salePrice ?? $product->price;
    }

    /**
     * Get discount percentage for display
     * 
     * @param Product $product
     * 
     * @return float|null Discount percentage, null if no active sale
     */
    public function getDiscountPercentage(Product $product): ?float
    {
        $sale = $this->getActiveFlashSale($product->id);

        if (!$sale) {
            return null;
        }

        $discount = (($product->price - $sale->sale_price) / $product->price) * 100;

        return round($discount, 2);
    }

    /**
     * Get time remaining for flash sale in seconds
     * 
     * @param Product $product
     * 
     * @return int|null Seconds remaining, null if no active sale
     */
    public function getTimeRemaining(Product $product): ?int
    {
        $sale = $this->getActiveFlashSale($product->id);

        if (!$sale) {
            return null;
        }

        $secondsRemaining = $sale->ends_at->diffInSeconds(now());

        return $secondsRemaining > 0 ? $secondsRemaining : null;
    }

    /**
     * Get formatted countdown (e.g., "2h 15m 30s")
     * 
     * @param Product $product
     * 
     * @return string|null Formatted countdown, null if no active sale
     */
    public function getFormattedCountdown(Product $product): ?string
    {
        $seconds = $this->getTimeRemaining($product);

        if (!$seconds) {
            return null;
        }

        $hours = intval($seconds / 3600);
        $minutes = intval(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m {$secs}s";
        } elseif ($minutes > 0) {
            return "{$minutes}m {$secs}s";
        } else {
            return "{$secs}s";
        }
    }

    /**
     * Record a purchase against flash sale quantity
     * 
     * @param int $productId
     * @param int $quantity
     * 
     * @return bool
     */
    public function recordSale(int $productId, int $quantity = 1): bool
    {
        try {
            $sale = $this->getActiveFlashSale($productId);

            if (!$sale) {
                return true; // No active sale, nothing to record
            }

            // Check if quantity limit would be exceeded
            if ($sale->quantity_limit && ($sale->quantity_sold + $quantity) > $sale->quantity_limit) {
                Log::warning('Flash sale quantity limit exceeded', [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);

                return false;
            }

            // Increment quantity sold
            $sale->increment('quantity_sold', $quantity);

            Log::info('Flash sale recorded', [
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error recording flash sale', ['product_id' => $productId, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Check if product has active flash sale
     * 
     * @param int $productId
     * 
     * @return bool
     */
    public function isOnSale(int $productId): bool
    {
        return $this->getActiveFlashSale($productId) !== null;
    }

    /**
     * Get all currently active flash sales (for display on home page, etc.)
     * 
     * @param int $limit
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveFlashSales(int $limit = 10)
    {
        return FlashSale::where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->with('product')
            ->latest('ends_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Deactivate expired flash sales (call this via scheduler)
     * 
     * @return int Number of sales deactivated
     */
    public function deactivateExpiredSales(): int
    {
        $updated = FlashSale::where('is_active', true)
            ->where('ends_at', '<=', now())
            ->update(['is_active' => false]);

        if ($updated > 0) {
            Log::info('Deactivated expired flash sales', ['count' => $updated]);
        }

        return $updated;
    }
}
