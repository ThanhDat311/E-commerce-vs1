<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class LowStockAlertsController extends Controller
{
    /**
     * Display the low stock alerts dashboard
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $statusFilter = $request->get('status', '');
        $categoryFilter = $request->get('category', '');
        $sortFilter = $request->get('sort', 'urgency');
        $searchFilter = $request->get('search', '');

        // Get low stock products
        $products = $this->getLowStockProducts($statusFilter, $categoryFilter, $sortFilter, $searchFilter);

        // Calculate summary counts
        $critical = count(array_filter($products, fn($p) => $p['status'] === 'critical'));
        $warning = count(array_filter($products, fn($p) => $p['status'] === 'warning'));
        $low = count(array_filter($products, fn($p) => $p['status'] === 'low'));
        $total = count($products);

        return view('admin.low-stock-alerts.index', [
            'products' => $products,
            'critical' => $critical,
            'warning' => $warning,
            'low' => $low,
            'total' => $total,
            'statusFilter' => $statusFilter,
            'categoryFilter' => $categoryFilter,
            'sortFilter' => $sortFilter,
            'searchFilter' => $searchFilter,
        ]);
    }

    /**
     * Get alert data for API endpoint
     */
    public function alertData(Request $request)
    {
        $statusFilter = $request->get('status', '');
        $categoryFilter = $request->get('category', '');
        $sortFilter = $request->get('sort', 'urgency');
        $searchFilter = $request->get('search', '');

        $products = $this->getLowStockProducts($statusFilter, $categoryFilter, $sortFilter, $searchFilter);

        return response()->json([
            'data' => $products,
            'total' => count($products),
            'critical' => count(array_filter($products, fn($p) => $p['status'] === 'critical')),
            'warning' => count(array_filter($products, fn($p) => $p['status'] === 'warning')),
            'low' => count(array_filter($products, fn($p) => $p['status'] === 'low')),
        ]);
    }

    /**
     * Get category summary data
     */
    public function categorySummary(Request $request)
    {
        $data = [
            [
                'category' => 'Electronics',
                'critical' => 3,
                'warning' => 5,
                'low' => 1,
                'total' => 9,
            ],
            [
                'category' => 'Fashion',
                'critical' => 1,
                'warning' => 4,
                'low' => 2,
                'total' => 7,
            ],
            [
                'category' => 'Home & Garden',
                'critical' => 2,
                'warning' => 3,
                'low' => 1,
                'total' => 6,
            ],
            [
                'category' => 'Sports',
                'critical' => 2,
                'warning' => 2,
                'low' => 2,
                'total' => 6,
            ],
        ];

        return response()->json($data);
    }

    /**
     * Mark product as restocked
     */
    public function markRestocked(Request $request, $productId)
    {
        // Validate request
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Log restock action
        // In production: Update inventory, log audit trail, notify team

        return response()->json([
            'success' => true,
            'message' => 'Product marked as restocked',
            'productId' => $productId,
            'quantity' => $request->get('quantity'),
        ]);
    }

    /**
     * Update stock threshold for product
     */
    public function updateThreshold(Request $request, $productId)
    {
        $request->validate([
            'threshold' => 'required|integer|min:1',
        ]);

        // In production: Update product threshold in database

        return response()->json([
            'success' => true,
            'message' => 'Threshold updated successfully',
            'productId' => $productId,
            'newThreshold' => $request->get('threshold'),
        ]);
    }

    /**
     * Export low stock data to CSV
     */
    public function export(Request $request)
    {
        $statusFilter = $request->get('status', '');
        $categoryFilter = $request->get('category', '');
        $sortFilter = $request->get('sort', 'urgency');
        $searchFilter = $request->get('search', '');

        $products = $this->getLowStockProducts($statusFilter, $categoryFilter, $sortFilter, $searchFilter);

        $filename = 'low-stock-alerts-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Encoding' => 'UTF-8',
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Status',
                'Product Name',
                'Category',
                'Current Stock',
                'Minimum Threshold',
                'Stock Level %',
                'Restock Quantity',
                'Urgency',
            ]);

            // Data rows
            foreach ($products as $product) {
                fputcsv($file, [
                    strtoupper($product['status']),
                    $product['name'] ?? '',
                    $product['category'] ?? '',
                    $product['current_stock'] ?? 0,
                    $product['min_threshold'] ?? 0,
                    round(($product['current_stock'] / $product['min_threshold']) * 100, 1) . '%',
                    $product['restock_qty'] ?? 0,
                    $product['status'] ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get low stock products
     */
    private function getLowStockProducts($status = '', $category = '', $sort = 'urgency', $search = '')
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Premium Wireless Headphones',
                'category' => 'electronics',
                'current_stock' => 12,
                'min_threshold' => 50,
                'restock_qty' => 100,
                'status' => 'critical',
                'level_percentage' => 24,
            ],
            [
                'id' => 2,
                'name' => 'Smart Watch Pro',
                'category' => 'electronics',
                'current_stock' => 8,
                'min_threshold' => 45,
                'restock_qty' => 75,
                'status' => 'critical',
                'level_percentage' => 18,
            ],
            [
                'id' => 3,
                'name' => 'Cotton T-Shirt Bundle',
                'category' => 'fashion',
                'current_stock' => 125,
                'min_threshold' => 150,
                'restock_qty' => 200,
                'status' => 'warning',
                'level_percentage' => 83,
            ],
            [
                'id' => 4,
                'name' => 'Yoga Mat Set',
                'category' => 'sports',
                'current_stock' => 98,
                'min_threshold' => 120,
                'restock_qty' => 60,
                'status' => 'warning',
                'level_percentage' => 82,
            ],
            [
                'id' => 5,
                'name' => 'Running Shoes Classic',
                'category' => 'sports',
                'current_stock' => 189,
                'min_threshold' => 200,
                'restock_qty' => 50,
                'status' => 'low',
                'level_percentage' => 95,
            ],
            [
                'id' => 6,
                'name' => 'Winter Jacket',
                'category' => 'fashion',
                'current_stock' => 156,
                'min_threshold' => 160,
                'restock_qty' => 40,
                'status' => 'low',
                'level_percentage' => 97,
            ],
            [
                'id' => 7,
                'name' => 'Desk Lamp LED',
                'category' => 'home',
                'current_stock' => 15,
                'min_threshold' => 60,
                'restock_qty' => 90,
                'status' => 'critical',
                'level_percentage' => 25,
            ],
            [
                'id' => 8,
                'name' => 'Bluetooth Speaker',
                'category' => 'electronics',
                'current_stock' => 85,
                'min_threshold' => 100,
                'restock_qty' => 50,
                'status' => 'warning',
                'level_percentage' => 85,
            ],
        ];

        // Apply status filter
        if ($status) {
            $products = array_filter($products, fn($p) => $p['status'] === $status);
        }

        // Apply category filter
        if ($category) {
            $products = array_filter($products, fn($p) => $p['category'] === $category);
        }

        // Apply search filter
        if ($search) {
            $search = strtolower($search);
            $products = array_filter($products, fn($p) => 
                stripos($p['name'], $search) !== false
            );
        }

        // Apply sorting
        usort($products, function($a, $b) use ($sort) {
            if ($sort === 'stock') {
                return $a['current_stock'] <=> $b['current_stock'];
            } elseif ($sort === 'name') {
                return strcmp($a['name'], $b['name']);
            } elseif ($sort === 'restock') {
                return $b['restock_qty'] <=> $a['restock_qty'];
            }
            // Default: urgency (critical first, then percentage)
            $statusOrder = ['critical' => 0, 'warning' => 1, 'low' => 2];
            $statusDiff = ($statusOrder[$a['status']] ?? 3) <=> ($statusOrder[$b['status']] ?? 3);
            if ($statusDiff !== 0) {
                return $statusDiff;
            }
            return $a['level_percentage'] <=> $b['level_percentage'];
        });

        return array_values($products);
    }
}
