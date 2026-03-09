<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * DealService
 *
 * Handles deal validation, product eligibility checks,
 * usage tracking, and scheduled expiry.
 */
class DealService
{
    /**
     * Check if a deal is currently active.
     */
    public function isDealActive(Deal $deal): bool
    {
        return $deal->isActive();
    }

    /**
     * Check if a deal is valid for a specific product.
     */
    public function isValidForProduct(Deal $deal, Product $product): bool
    {
        if (! $this->isDealActive($deal)) {
            return false;
        }

        return match ($deal->apply_scope) {
            'global' => true,
            'vendor' => $product->vendor_id === $deal->vendor_id,
            'category' => $deal->categories->contains('id', $product->category_id),
            'product' => $deal->products->contains('id', $product->id),
            default => false,
        };
    }

    /**
     * Get all active deals applicable to a product, sorted by priority (highest first).
     *
     * @return Collection<Deal>
     */
    public function getActiveDealsForProduct(Product $product): Collection
    {
        $deals = Cache::remember('deals_active_list', now()->addMinutes(15), function () {
            return Deal::where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>', now())
                ->where(function ($query) {
                    $query->whereNull('usage_limit')
                        ->orWhereColumn('usage_count', '<', 'usage_limit');
                })
                ->orderByDesc('priority')
                ->with(['products', 'categories'])
                ->get();
        });

        return $deals->filter(fn(Deal $deal) => $this->isValidForProduct($deal, $product))->values();
    }

    /**
     * Increment the usage count of a deal atomically.
     */
    public function increaseUsage(Deal $deal): void
    {
        $deal->increment('usage_count');
    }

    /**
     * Auto-expire deals whose end_date has passed.
     * Called by the scheduler hourly.
     */
    public function expireDeals(): int
    {
        $updated = Deal::where('status', 'active')
            ->where('end_date', '<=', now())
            ->update(['status' => 'expired']);

        if ($updated > 0) {
            Log::info('Deals auto-expired', ['count' => $updated]);
        }

        return $updated;
    }
}
