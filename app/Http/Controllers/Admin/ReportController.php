<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Reports & Analytics Dashboard
     */
    public function index(Request $request)
    {
        // ── Date Range ──────────────────────────────────────
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $startDate = $request->input('start_date', Carbon::parse($endDate)->subDays(29)->format('Y-m-d'));
        $periodDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;

        $prevEndDate = Carbon::parse($startDate)->subDay()->format('Y-m-d');
        $prevStartDate = Carbon::parse($prevEndDate)->subDays($periodDays - 1)->format('Y-m-d');

        // ── Stat Cards ──────────────────────────────────────
        $totalSales = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->sum('total');
        $prevTotalSales = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$prevStartDate.' 00:00:00', $prevEndDate.' 23:59:59'])
            ->sum('total');
        $salesTrend = $prevTotalSales > 0
            ? round(($totalSales - $prevTotalSales) / $prevTotalSales * 100, 1)
            : 0;

        $newUsers = User::whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])->count();
        $prevNewUsers = User::whereBetween('created_at', [$prevStartDate.' 00:00:00', $prevEndDate.' 23:59:59'])->count();
        $usersTrend = $prevNewUsers > 0
            ? round(($newUsers - $prevNewUsers) / $prevNewUsers * 100, 1)
            : 0;

        $activeVendors = User::where('role_id', 4)
            ->whereHas('products.orderItems.order', function ($q) use ($startDate, $endDate) {
                $q->where('payment_status', 'paid')
                    ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
            })
            ->count();
        $prevActiveVendors = User::where('role_id', 4)
            ->whereHas('products.orderItems.order', function ($q) use ($prevStartDate, $prevEndDate) {
                $q->where('payment_status', 'paid')
                    ->whereBetween('created_at', [$prevStartDate.' 00:00:00', $prevEndDate.' 23:59:59']);
            })
            ->count();
        $vendorsTrend = $prevActiveVendors > 0
            ? round(($activeVendors - $prevActiveVendors) / $prevActiveVendors * 100, 1)
            : 0;

        $avgOrderValue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->avg('total') ?? 0;
        $prevAvgOrderValue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$prevStartDate.' 00:00:00', $prevEndDate.' 23:59:59'])
            ->avg('total') ?? 0;
        $avgTrend = $prevAvgOrderValue > 0
            ? round(($avgOrderValue - $prevAvgOrderValue) / $prevAvgOrderValue * 100, 1)
            : 0;

        // ── Daily Sales Chart ───────────────────────────────
        $dailySalesRaw = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $dailyLabels = [];
        $dailyValues = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        while ($current->lte($end)) {
            $key = $current->format('Y-m-d');
            $dailyLabels[] = $current->format('d');
            $dailyValues[] = (float) ($dailySalesRaw[$key] ?? 0);
            $current->addDay();
        }

        // ── Monthly Sales Chart ─────────────────────────────
        $monthlySalesRaw = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(12)->startOfMonth())
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyLabels = [];
        $monthlyValues = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $key = $month->format('Y-m');
            $monthlyLabels[] = $month->format('M');
            $monthlyValues[] = (float) ($monthlySalesRaw[$key] ?? 0);
        }

        // ── Sales by Category (Doughnut) ────────────────────
        $categoryData = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->selectRaw('categories.name, SUM(order_items.total) as revenue')
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        $totalCategoryRevenue = $categoryData->sum('revenue');
        $categoryLabels = $categoryData->pluck('name')->toArray();
        $categoryValues = $categoryData->pluck('revenue')->map(fn ($v) => (float) $v)->toArray();
        $categoryPercentages = $totalCategoryRevenue > 0
            ? $categoryData->pluck('revenue')->map(fn ($v) => round($v / $totalCategoryRevenue * 100))->toArray()
            : [];

        // ── Vendor Performance ──────────────────────────────
        $vendorsQuery = User::where('role_id', 4)
            ->withCount(['products'])
            ->addSelect([
                'total_sales' => OrderItem::selectRaw('COALESCE(SUM(order_items.total), 0)')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.payment_status', 'paid')
                    ->whereColumn('products.vendor_id', 'users.id'),
                'avg_rating' => DB::table('reviews')
                    ->selectRaw('COALESCE(AVG(reviews.rating), 0)')
                    ->join('products', 'reviews.product_id', '=', 'products.id')
                    ->whereColumn('products.vendor_id', 'users.id'),
            ]);

        if ($search = $request->input('vendor_search')) {
            $vendorsQuery->where('name', 'like', "%{$search}%");
        }

        $vendors = $vendorsQuery->orderByDesc('total_sales')->paginate(10)->withQueryString();

        return view('pages.admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalSales',
            'salesTrend',
            'newUsers',
            'usersTrend',
            'activeVendors',
            'vendorsTrend',
            'avgOrderValue',
            'avgTrend',
            'dailyLabels',
            'dailyValues',
            'monthlyLabels',
            'monthlyValues',
            'categoryLabels',
            'categoryValues',
            'categoryPercentages',
            'vendors',
        ));
    }

    /**
     * Export sales report as CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $filename = "report-{$startDate}-to-{$endDate}.csv";

        return response()->streamDownload(function () use ($startDate, $endDate) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Date', 'Orders', 'Revenue', 'Avg Order Value']);

            Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total) as revenue, AVG(total) as avg_value')
                ->groupBy('date')
                ->orderBy('date')
                ->chunk(200, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->date,
                            $row->orders,
                            '$'.number_format($row->revenue, 2),
                            '$'.number_format($row->avg_value, 2),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /**
     * Export PDF (renders a print-friendly view)
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $revenues = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, SUM(total) as total_revenue, COUNT(id) as total_orders')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $summary = [
            'total_revenue' => $revenues->sum('total_revenue'),
            'total_orders' => $revenues->sum('total_orders'),
        ];

        return view('pages.admin.reports.pdf', compact('revenues', 'startDate', 'endDate', 'summary'));
    }

    /**
     * Revenue report (legacy)
     */
    public function revenue(Request $request)
    {
        return redirect()->route('admin.reports.index', $request->all());
    }

    /**
     * Top selling products
     */
    public function topProducts(Request $request)
    {
        $topProducts = OrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(price * quantity) as total_revenue')
        )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(20)
            ->get();

        return view('admin.reports.top_products', compact('topProducts'));
    }

    /**
     * Low stock products
     */
    public function lowStock(Request $request)
    {
        $threshold = 10;

        $lowStockProducts = Product::where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity', 'asc')
            ->get();

        return view('admin.reports.low_stock', compact('lowStockProducts'));
    }
}
