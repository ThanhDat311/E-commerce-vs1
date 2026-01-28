<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class TopSellingProductsController extends Controller
{
    /**
     * Display the top selling products dashboard
     */
    public function index(Request $request)
    {
        // Get date range from request or use default (last 30 days)
        $startDate = $request->get('startDate')
            ? Carbon::parse($request->get('startDate'))
            : now()->subDays(30);

        $endDate = $request->get('endDate')
            ? Carbon::parse($request->get('endDate'))
            : now();

        // Get filters
        $category = $request->get('category', '');
        $sortBy = $request->get('sortBy', 'units');

        // Get analytics data
        $analytics = $this->getAnalyticsData($startDate, $endDate, $category, $sortBy);

        return view('admin.top-selling-products.index', [
            'analytics' => $analytics,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'category' => $category,
            'sortBy' => $sortBy,
            'topProduct' => $analytics['topProduct'] ?? [],
            'productsCount' => $analytics['productsCount'] ?? 0,
            'avgRevenue' => $analytics['avgRevenue'] ?? 0,
        ]);
    }

    /**
     * Get product performance data for API
     */
    public function productData(Request $request)
    {
        $startDate = Carbon::parse($request->get('startDate'));
        $endDate = Carbon::parse($request->get('endDate'));
        $category = $request->get('category', '');
        $sortBy = $request->get('sortBy', 'units');

        // Build query for product sales data
        $query = collect([
            ['name' => 'Premium Wireless Headphones', 'category' => 'electronics', 'units' => 1245, 'revenue' => 45230, 'trend' => 12],
            ['name' => 'Smart Watch Pro', 'category' => 'electronics', 'units' => 987, 'revenue' => 38450, 'trend' => 8],
            ['name' => 'Cotton T-Shirt Bundle', 'category' => 'fashion', 'units' => 2156, 'revenue' => 21560, 'trend' => -5],
            ['name' => 'Yoga Mat Set', 'category' => 'sports', 'units' => 1834, 'revenue' => 18340, 'trend' => 22],
            ['name' => 'Desk Lamp LED', 'category' => 'home', 'units' => 1456, 'revenue' => 29120, 'trend' => 15],
            ['name' => 'Running Shoes Classic', 'category' => 'sports', 'units' => 892, 'revenue' => 17840, 'trend' => -8],
            ['name' => 'Winter Jacket', 'category' => 'fashion', 'units' => 734, 'revenue' => 26424, 'trend' => 18],
            ['name' => 'Bluetooth Speaker', 'category' => 'electronics', 'units' => 678, 'revenue' => 13560, 'trend' => -3],
            ['name' => 'Plant Pot Set', 'category' => 'home', 'units' => 1012, 'revenue' => 10120, 'trend' => 24],
            ['name' => 'Phone Screen Protector', 'category' => 'electronics', 'units' => 2345, 'revenue' => 4690, 'trend' => -6],
        ]);

        // Apply category filter
        if ($category) {
            $query = $query->where('category', $category);
        }

        // Sort results
        if ($sortBy === 'revenue') {
            $query = $query->sortByDesc('revenue');
        } elseif ($sortBy === 'trend') {
            $query = $query->sortByDesc('trend');
        } else {
            $query = $query->sortByDesc('units');
        }

        return response()->json([
            'data' => $query->values(),
            'total' => $query->count(),
        ]);
    }

    /**
     * Get category breakdown data
     */
    public function categoryBreakdown(Request $request)
    {
        $startDate = Carbon::parse($request->get('startDate'));
        $endDate = Carbon::parse($request->get('endDate'));

        // Category performance data
        $data = [
            [
                'category' => 'Electronics',
                'revenue' => 97240,
                'units' => 3910,
                'products' => 3,
                'percentage' => 42,
            ],
            [
                'category' => 'Fashion',
                'revenue' => 47984,
                'units' => 2890,
                'products' => 2,
                'percentage' => 21,
            ],
            [
                'category' => 'Home & Garden',
                'revenue' => 39240,
                'units' => 2468,
                'products' => 2,
                'percentage' => 17,
            ],
            [
                'category' => 'Sports',
                'revenue' => 36324,
                'units' => 3626,
                'products' => 3,
                'percentage' => 16,
            ],
        ];

        return response()->json([
            'data' => $data,
            'total_revenue' => array_sum(array_column($data, 'revenue')),
        ]);
    }

    /**
     * Get trend comparison data
     */
    public function trendComparison(Request $request)
    {
        $startDate = Carbon::parse($request->get('startDate'));
        $endDate = Carbon::parse($request->get('endDate'));

        // Get previous period dates
        $days = $startDate->diffInDays($endDate);
        $previousStart = $startDate->copy()->subDays($days);
        $previousEnd = $startDate->copy()->subDay();

        // Trend comparison data
        $data = [
            [
                'product' => 'Premium Wireless Headphones',
                'current_units' => 1245,
                'previous_units' => 1110,
                'current_revenue' => 45230,
                'previous_revenue' => 40050,
                'units_change' => 12,
                'revenue_change' => 13,
            ],
            [
                'product' => 'Smart Watch Pro',
                'current_units' => 987,
                'previous_units' => 915,
                'current_revenue' => 38450,
                'previous_revenue' => 35580,
                'units_change' => 8,
                'revenue_change' => 8,
            ],
            [
                'product' => 'Cotton T-Shirt Bundle',
                'current_units' => 2156,
                'previous_units' => 2270,
                'current_revenue' => 21560,
                'previous_revenue' => 22700,
                'units_change' => -5,
                'revenue_change' => -5,
            ],
            [
                'product' => 'Yoga Mat Set',
                'current_units' => 1834,
                'previous_units' => 1504,
                'current_revenue' => 18340,
                'previous_revenue' => 15040,
                'units_change' => 22,
                'revenue_change' => 22,
            ],
            [
                'product' => 'Desk Lamp LED',
                'current_units' => 1456,
                'previous_units' => 1266,
                'current_revenue' => 29120,
                'previous_revenue' => 25320,
                'units_change' => 15,
                'revenue_change' => 15,
            ],
        ];

        return response()->json([
            'data' => $data,
            'period' => [
                'current_start' => $startDate->format('Y-m-d'),
                'current_end' => $endDate->format('Y-m-d'),
                'previous_start' => $previousStart->format('Y-m-d'),
                'previous_end' => $previousEnd->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * Export product data to CSV
     */
    public function export(Request $request)
    {
        $startDate = $request->get('startDate')
            ? Carbon::parse($request->get('startDate'))
            : now()->subDays(30);

        $endDate = $request->get('endDate')
            ? Carbon::parse($request->get('endDate'))
            : now();

        $category = $request->get('category', '');
        $sortBy = $request->get('sortBy', 'units');

        // Get analytics data
        $analytics = $this->getAnalyticsData($startDate, $endDate, $category, $sortBy);
        $products = $analytics['products'] ?? [];

        // Build CSV
        $filename = 'top-products-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Encoding' => 'UTF-8',
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Headers
            fputcsv($file, [
                'Rank',
                'Product Name',
                'Category',
                'Units Sold',
                'Revenue',
                'Avg Price',
                'Trend %',
                'Share of Revenue',
            ]);

            // Data rows
            foreach ($products as $index => $product) {
                fputcsv($file, [
                    $index + 1,
                    $product['name'] ?? '',
                    $product['category'] ?? '',
                    $product['units'] ?? 0,
                    $product['revenue'] ?? 0,
                    round(($product['revenue'] ?? 0) / ($product['units'] ?? 1), 2),
                    $product['trend'] ?? 0,
                    $product['share'] ?? 0,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get top selling products analytics data
     */
    private function getAnalyticsData($startDate, $endDate, $category = '', $sortBy = 'units')
    {
        // Product sales data
        $products = [
            ['name' => 'Premium Wireless Headphones', 'category' => 'electronics', 'units' => 1245, 'revenue' => 45230, 'trend' => 12, 'share' => 18],
            ['name' => 'Smart Watch Pro', 'category' => 'electronics', 'units' => 987, 'revenue' => 38450, 'trend' => 8, 'share' => 15],
            ['name' => 'Cotton T-Shirt Bundle', 'category' => 'fashion', 'units' => 2156, 'revenue' => 21560, 'trend' => -5, 'share' => 8],
            ['name' => 'Yoga Mat Set', 'category' => 'sports', 'units' => 1834, 'revenue' => 18340, 'trend' => 22, 'share' => 7],
            ['name' => 'Desk Lamp LED', 'category' => 'home', 'units' => 1456, 'revenue' => 29120, 'trend' => 15, 'share' => 11],
            ['name' => 'Running Shoes Classic', 'category' => 'sports', 'units' => 892, 'revenue' => 17840, 'trend' => -8, 'share' => 7],
            ['name' => 'Winter Jacket', 'category' => 'fashion', 'units' => 734, 'revenue' => 26424, 'trend' => 18, 'share' => 10],
            ['name' => 'Bluetooth Speaker', 'category' => 'electronics', 'units' => 678, 'revenue' => 13560, 'trend' => -3, 'share' => 5],
            ['name' => 'Plant Pot Set', 'category' => 'home', 'units' => 1012, 'revenue' => 10120, 'trend' => 24, 'share' => 4],
            ['name' => 'Phone Screen Protector', 'category' => 'electronics', 'units' => 2345, 'revenue' => 4690, 'trend' => -6, 'share' => 2],
        ];

        // Apply category filter
        if ($category) {
            $products = array_filter($products, function ($product) use ($category) {
                return $product['category'] === $category;
            });
        }

        // Sort results
        usort($products, function ($a, $b) use ($sortBy) {
            if ($sortBy === 'revenue') {
                return $b['revenue'] <=> $a['revenue'];
            } elseif ($sortBy === 'trend') {
                return $b['trend'] <=> $a['trend'];
            }
            return $b['units'] <=> $a['units'];
        });

        // Limit to top 10
        $products = array_slice($products, 0, 10);

        // Calculate totals
        $totalRevenue = array_sum(array_column($products, 'revenue'));
        $totalUnits = array_sum(array_column($products, 'units'));
        $productCount = count($products);
        $avgRevenue = $productCount > 0 ? round($totalRevenue / $productCount, 2) : 0;

        return [
            'products' => $products,
            'topProduct' => $products[0] ?? [],
            'totalRevenue' => $totalRevenue,
            'totalUnits' => $totalUnits,
            'productsCount' => 48, // Total products in system
            'avgRevenue' => $avgRevenue,
            'topProductTrend' => $products[0]['trend'] ?? 0,
        ];
    }
}
