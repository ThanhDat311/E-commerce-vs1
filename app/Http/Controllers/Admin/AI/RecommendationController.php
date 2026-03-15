<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    /**
     * Display recommendation analytics and configuration.
     */
    public function index(): View
    {
        // Top frequently bought together - find products that share the same orders
        $topProducts = Product::query()
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(10)
            ->get();

        // Products with no sales (potential candidates to feature/recommend)
        $unsoldProducts = Product::query()
            ->whereDoesntHave('orderItems')
            ->take(20)
            ->get();

        // Simple cross-sell insights: most common product pairings
        $pairings = OrderItem::select('product_id')
            ->selectRaw('COUNT(*) as order_count')
            ->groupBy('product_id')
            ->orderByDesc('order_count')
            ->take(5)
            ->with('product')
            ->get();

        $stats = [
            'total_products' => Product::count(),
            'products_sold' => Product::has('orderItems')->count(),
            'unsold_products' => Product::doesntHave('orderItems')->count(),
            'top_seller_name' => $topProducts->first()?->name ?? 'N/A',
        ];

        return view('pages.admin.ai-recommendations.index', compact('topProducts', 'unsoldProducts', 'pairings', 'stats'));
    }
}
