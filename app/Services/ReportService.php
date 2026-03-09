<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Revenue summary for a date range.
     *
     * @return array{total_revenue: float, total_orders: int, avg_order_value: float, trend_pct: float}
     */
    public function revenueSummary(string $startDate, string $endDate): array
    {
        $current = $this->revenueForPeriod($startDate, $endDate);
        $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $prevEnd = Carbon::parse($startDate)->subDay()->format('Y-m-d');
        $prevStart = Carbon::parse($prevEnd)->subDays($days - 1)->format('Y-m-d');
        $previous = $this->revenueForPeriod($prevStart, $prevEnd);

        $trend = $previous['total_revenue'] > 0
            ? round(($current['total_revenue'] - $previous['total_revenue']) / $previous['total_revenue'] * 100, 1)
            : 0;

        return array_merge($current, ['trend_pct' => $trend]);
    }

    /**
     * Daily revenue series for chart rendering.
     *
     * @return array{labels: string[], values: float[]}
     */
    public function dailySeries(string $startDate, string $endDate): array
    {
        $raw = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $values = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            $key = $current->format('Y-m-d');
            $labels[] = $current->format('d/m');
            $values[] = (float) ($raw[$key] ?? 0);
            $current->addDay();
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Top-selling products by quantity and revenue.
     *
     * @return \Illuminate\Support\Collection
     */
    public function topProducts(int $limit = 20)
    {
        return OrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(price * quantity) as total_revenue')
        )
            ->with('product:id,name,image_url,stock_quantity')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    /**
     * Products below a given stock threshold.
     *
     * @return \Illuminate\Support\Collection
     */
    public function lowStockProducts(int $threshold = 10)
    {
        return Product::withoutGlobalScopes()
            ->where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity', 'asc')
            ->get(['id', 'name', 'sku', 'stock_quantity', 'category_id']);
    }

    /**
     * Revenue grouped by category for a date range.
     *
     * @return \Illuminate\Support\Collection
     */
    public function revenueByCategory(string $startDate, string $endDate, int $limit = 5)
    {
        return OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
            ->selectRaw('categories.name, SUM(order_items.total) as revenue')
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();
    }

    /**
     * Build rows for a CSV export.
     */
    public function csvRows(string $startDate, string $endDate): \Illuminate\Support\LazyCollection
    {
        return Order::where('payment_status', 'paid')
            ->whereBetween('created_at', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total) as revenue, AVG(total) as avg_value')
            ->groupBy('date')
            ->orderBy('date')
            ->cursor();
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    /**
     * @return array{total_revenue: float, total_orders: int, avg_order_value: float}
     */
    private function revenueForPeriod(string $startDate, string $endDate): array
    {
        $result = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
            ->selectRaw('COALESCE(SUM(total), 0) as revenue, COUNT(*) as orders, COALESCE(AVG(total), 0) as avg_value')
            ->first();

        return [
            'total_revenue' => (float) $result->revenue,
            'total_orders' => (int) $result->orders,
            'avg_order_value' => (float) $result->avg_value,
        ];
    }
}
