<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * PriceCalculatorService
 *
 * Handles discount calculations (percent, fixed, BOGO)
 * and selects the best (highest discount) deal for a product.
 */
class PriceCalculatorService
{
    /**
     * Apply a percentage discount.
     */
    public function calculatePercent(float $price, float $discountValue): float
    {
        $discount = ($price * $discountValue) / 100;

        return max(0, round($price - $discount, 2));
    }

    /**
     * Apply a fixed amount discount.
     */
    public function calculateFixed(float $price, float $discountValue): float
    {
        return max(0, round($price - $discountValue, 2));
    }

    /**
     * Calculate BOGO (Buy X Get Y): cheapest item in each pair is free.
     * Returns the total discount amount for the given item list.
     *
     * @param  array<array{price: float, quantity: int}>  $items
     */
    public function calculateBOGO(array $items): float
    {
        $discount = 0.0;

        foreach ($items as $item) {
            $pairsCount = intdiv((int) $item['quantity'], 2);
            $discount += $pairsCount * (float) $item['price'];
        }

        return round($discount, 2);
    }

    /**
     * Calculate the discounted price for a product given a deal.
     *
     * @return array{discounted_price: float, discount_amount: float}
     */
    public function applyDeal(Product $product, Deal $deal): array
    {
        $price = (float) $product->price;

        // Check for product-specific special_price first
        $pivot = $deal->products()->where('product_id', $product->id)->first();
        if ($pivot && $pivot->pivot->special_price !== null) {
            $discountedPrice = (float) $pivot->pivot->special_price;

            return [
                'discounted_price' => max(0, $discountedPrice),
                'discount_amount' => max(0, round($price - $discountedPrice, 2)),
            ];
        }

        $discountedPrice = match ($deal->discount_type) {
            'percent' => $this->calculatePercent($price, (float) $deal->discount_value),
            'fixed' => $this->calculateFixed($price, (float) $deal->discount_value),
            'flash' => $this->calculatePercent($price, (float) $deal->discount_value),
            default => $price,
        };

        return [
            'discounted_price' => $discountedPrice,
            'discount_amount' => max(0, round($price - $discountedPrice, 2)),
        ];
    }

    /**
     * Given a collection of active deals for a product, pick the one
     * that provides the highest discount amount.
     *
     * Vendor deals cannot override Admin global deals (vendor_id is not null
     * for vendor-created deals).
     *
     * @param  Collection<Deal>  $deals
     * @return array{discounted_price: float, discount_amount: float, deal: Deal|null}
     */
    public function applyBestDeal(Product $product, Collection $deals): array
    {
        if ($deals->isEmpty()) {
            return [
                'discounted_price' => (float) $product->price,
                'discount_amount' => 0.0,
                'deal' => null,
            ];
        }

        // Separate global/admin deals from vendor deals
        $adminDeals = $deals->filter(fn (Deal $d) => $d->apply_scope === 'global' || is_null($d->vendor_id));
        $vendorDeals = $deals->filter(fn (Deal $d) => ! is_null($d->vendor_id) && $d->apply_scope !== 'global');

        // If there are admin/global deals, vendor deals cannot override them
        $eligibleDeals = $adminDeals->isNotEmpty() ? $adminDeals : $vendorDeals;

        $bestDeal = null;
        $bestDiscountedPrice = (float) $product->price;
        $bestDiscountAmount = 0.0;

        foreach ($eligibleDeals as $deal) {
            ['discounted_price' => $dp, 'discount_amount' => $da] = $this->applyDeal($product, $deal);

            if ($da > $bestDiscountAmount) {
                $bestDiscountAmount = $da;
                $bestDiscountedPrice = $dp;
                $bestDeal = $deal;
            }
        }

        return [
            'discounted_price' => $bestDiscountedPrice,
            'discount_amount' => $bestDiscountAmount,
            'deal' => $bestDeal,
        ];
    }
}
