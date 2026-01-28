<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class RevenueAnalyticsController extends Controller
{
    /**
     * Display the revenue analytics dashboard
     */
    public function index(Request $request)
    {
        // Get date range from request or use default (current month)
        $startDate = $request->get('startDate')
            ? Carbon::parse($request->get('startDate'))
            : now()->startOfMonth();

        $endDate = $request->get('endDate')
            ? Carbon::parse($request->get('endDate'))
            : now();

        // Get comparison period
        $comparisonPeriod = $request->get('comparisonPeriod', 'previous');

        // Calculate comparison dates
        $comparisonStart = $this->getComparisonStartDate($startDate, $comparisonPeriod);
        $comparisonEnd = $this->getComparisonEndDate($startDate, $comparisonPeriod);

        // Get analytics data
        $analytics = $this->getAnalyticsData($startDate, $endDate, $comparisonStart, $comparisonEnd);

        return view('admin.revenue-analytics.index', [
            'analytics' => $analytics,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'comparisonStart' => $comparisonStart,
            'comparisonEnd' => $comparisonEnd,
            'comparisonPeriod' => $comparisonPeriod,
        ]);
    }

    /**
     * Get revenue data for the specified date range
     */
    public function revenueData(Request $request)
    {
        $startDate = Carbon::parse($request->get('startDate'));
        $endDate = Carbon::parse($request->get('endDate'));

        // Generate daily revenue data
        $dailyRevenue = [];
        $currentDate = $startDate->clone();

        while ($currentDate <= $endDate) {
            $dailyRevenue[] = [
                'date' => $currentDate->format('Y-m-d'),
                'revenue' => rand(15000, 35000) / 100, // Random revenue for demo
                'orders' => rand(80, 200),
            ];
            $currentDate->addDay();
        }

        return response()->json($dailyRevenue);
    }

    /**
     * Get revenue by category
     */
    public function categoryBreakdown(Request $request)
    {
        return response()->json([
            [
                'name' => 'Electronics',
                'revenue' => 78240,
                'percentage' => 42,
                'color' => '#2563eb',
            ],
            [
                'name' => 'Fashion',
                'revenue' => 52310,
                'percentage' => 28,
                'color' => '#a855f7',
            ],
            [
                'name' => 'Home & Garden',
                'revenue' => 38145,
                'percentage' => 20,
                'color' => '#16a34a',
            ],
            [
                'name' => 'Sports',
                'revenue' => 17755,
                'percentage' => 10,
                'color' => '#ea580c',
            ],
        ]);
    }

    /**
     * Get revenue by day of week
     */
    public function dayOfWeekBreakdown(Request $request)
    {
        return response()->json([
            ['day' => 'Monday', 'revenue' => 28200, 'orders' => 220],
            ['day' => 'Tuesday', 'revenue' => 25100, 'orders' => 198],
            ['day' => 'Wednesday', 'revenue' => 30800, 'orders' => 242],
            ['day' => 'Thursday', 'revenue' => 29500, 'orders' => 231],
            ['day' => 'Friday', 'revenue' => 33600, 'orders' => 264],
            ['day' => 'Saturday', 'revenue' => 27300, 'orders' => 215],
            ['day' => 'Sunday', 'revenue' => 12000, 'orders' => 94],
        ]);
    }

    /**
     * Export revenue report
     */
    public function export(Request $request)
    {
        $startDate = Carbon::parse($request->get('startDate'));
        $endDate = Carbon::parse($request->get('endDate'));

        $filename = "revenue-report-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}.csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

        // Generate CSV content
        $csvData = "Date,Revenue,Orders,Avg Order Value,Conversion Rate\n";

        $currentDate = $startDate->clone();
        while ($currentDate <= $endDate) {
            $revenue = rand(15000, 35000);
            $orders = rand(80, 200);
            $avgOrderValue = $orders > 0 ? ($revenue / 100) / $orders : 0;
            $conversionRate = number_format(rand(250, 400) / 100, 2);

            $csvData .= $currentDate->format('Y-m-d') . ","
                . number_format($revenue / 100, 2) . ","
                . $orders . ","
                . number_format($avgOrderValue, 2) . ","
                . $conversionRate . "%\n";

            $currentDate->addDay();
        }

        return response()->make($csvData, 200, $headers);
    }

    /**
     * Calculate comparison start date based on period
     */
    private function getComparisonStartDate($startDate, $period)
    {
        $start = $startDate->clone();

        switch ($period) {
            case 'last-year':
                return $start->subYear();
            case 'custom':
                // For custom, would need additional request parameter
                return $start->subMonth();
            case 'previous':
            default:
                $daysDifference = $startDate->diffInDays(now());
                return $start->subDays($daysDifference);
        }
    }

    /**
     * Calculate comparison end date based on period
     */
    private function getComparisonEndDate($startDate, $period)
    {
        $end = $startDate->clone()->addDay();

        switch ($period) {
            case 'last-year':
                return $end->subYear();
            case 'custom':
                return $end->subMonth();
            case 'previous':
            default:
                return $end->subDay();
        }
    }

    /**
     * Get analytics summary data
     */
    private function getAnalyticsData($startDate, $endDate, $comparisonStart, $comparisonEnd)
    {
        return [
            // Current period
            'totalRevenue' => 186450,
            'averageOrderValue' => 127.35,
            'totalOrders' => 1462,
            'conversionRate' => 3.24,
            'totalVisitors' => 45072,

            // Previous period
            'prevTotalRevenue' => 165734,
            'prevAverageOrderValue' => 123.41,
            'prevTotalOrders' => 1344,
            'prevConversionRate' => 3.27,
            'prevTotalVisitors' => 41020,

            // Category breakdown
            'categoryBreakdown' => [
                ['name' => 'Electronics', 'revenue' => 78240, 'percentage' => 42],
                ['name' => 'Fashion', 'revenue' => 52310, 'percentage' => 28],
                ['name' => 'Home & Garden', 'revenue' => 38145, 'percentage' => 20],
                ['name' => 'Sports', 'revenue' => 17755, 'percentage' => 10],
            ],

            // Daily breakdown
            'dailyBreakdown' => [
                ['day' => 'Mon', 'revenue' => 28200, 'percentage' => 15.1],
                ['day' => 'Tue', 'revenue' => 25100, 'percentage' => 13.5],
                ['day' => 'Wed', 'revenue' => 30800, 'percentage' => 16.5],
                ['day' => 'Thu', 'revenue' => 29500, 'percentage' => 15.8],
                ['day' => 'Fri', 'revenue' => 33600, 'percentage' => 18.0],
                ['day' => 'Sat', 'revenue' => 27300, 'percentage' => 14.6],
                ['day' => 'Sun', 'revenue' => 12000, 'percentage' => 6.4],
            ],
        ];
    }
}
