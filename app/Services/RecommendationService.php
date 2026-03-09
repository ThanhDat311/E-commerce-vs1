<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    /**
     * Get products frequently purchased together with the given product.
     * Uses a collaborative filtering approach based on order history.
     */
    public function purchasedTogether(Product $product, int $limit = 4)
    {
        return Cache::remember("product_{$product->id}_purchased_together", 3600, function () use ($product, $limit) {
            // Find orders that contain this product
            $orderIds = OrderItem::where('product_id', $product->id)
                ->pluck('order_id');

            if ($orderIds->isEmpty()) {
                return collect();
            }

            // Find other products in those same orders, ordered by frequency
            $recommendedProductIds = OrderItem::whereIn('order_id', $orderIds)
                ->where('product_id', '!=', $product->id)
                ->select('product_id', DB::raw('count(*) as frequency'))
                ->groupBy('product_id')
                ->orderByDesc('frequency')
                ->limit($limit)
                ->pluck('product_id');

            if ($recommendedProductIds->isEmpty()) {
                return collect();
            }

            // Fetch the actual products and preserve the frequency order
            $products = Product::whereIn('id', $recommendedProductIds)
                ->get()
                ->keyBy('id');

            // Map IDs back to products to maintain sorted order
            return $recommendedProductIds->map(fn ($id) => $products->get($id))->filter();
        });
    }

    /**
     * Get products in the same category.
     */
    public function categoryBased(Product $product, int $limit = 4)
    {
        return Cache::remember("product_{$product->id}_category_based", 3600, function () use ($product, $limit) {
            return Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get top trending products (most sold in last 30 days).
     */
    public function trending(int $limit = 4)
    {
        return Cache::remember('trending_products', 3600, function () use ($limit) {
            $last30Days = now()->subDays(30);

            $trendingIds = OrderItem::whereHas('order', function ($query) use ($last30Days) {
                $query->where('created_at', '>=', $last30Days)
                    ->whereNotIn('order_status', ['cancelled']);
            })
                ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->limit($limit)
                ->pluck('product_id');

            if ($trendingIds->isEmpty()) {
                // Fallback to latest products if no sales data
                return Product::latest()->limit($limit)->get();
            }

            $products = Product::whereIn('id', $trendingIds)->get()->keyBy('id');

            return $trendingIds->map(fn ($id) => $products->get($id))->filter()->values();
        });
    }
}
