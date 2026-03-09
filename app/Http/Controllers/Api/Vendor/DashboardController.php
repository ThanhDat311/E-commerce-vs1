<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use ApiResponse;

    /**
     * Return vendor dashboard metrics.
     */
    public function index(): JsonResponse
    {
        $vendorId = Auth::id();

        $totalProducts = Product::withoutGlobalScopes()
            ->where('vendor_id', $vendorId)
            ->count();

        $outOfStock = Product::withoutGlobalScopes()
            ->where('vendor_id', $vendorId)
            ->where('stock_quantity', '<=', 0)
            ->count();

        $lowStockCount = Product::withoutGlobalScopes()
            ->where('vendor_id', $vendorId)
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<', 5)
            ->count();

        $avgRating = Product::withoutGlobalScopes()
            ->where('vendor_id', $vendorId)
            ->withAvg('ratings', 'rating')
            ->get()
            ->avg('ratings_avg_rating') ?? 0;

        $totalRevenue = Order::withoutGlobalScopes()
            ->whereHas('orderItems.product', fn ($q) => $q->where('vendor_id', $vendorId))
            ->where('payment_status', 'paid')
            ->sum('total');

        $recentOrders = Order::withoutGlobalScopes()
            ->whereHas('orderItems.product', fn ($q) => $q->where('vendor_id', $vendorId))
            ->with(['user', 'orderItems.product'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return $this->successResponse([
            'metrics' => [
                'total_revenue' => round($totalRevenue, 2),
                'total_products' => $totalProducts,
                'out_of_stock' => $outOfStock,
                'low_stock' => $lowStockCount,
                'average_rating' => round($avgRating, 2),
            ],
            'recent_orders' => OrderResource::collection($recentOrders),
        ], 'Dashboard data retrieved successfully.');
    }
}
