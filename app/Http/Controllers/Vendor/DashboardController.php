<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the vendor dashboard
     */
    public function index()
    {
        $vendor = Auth::user();

        // Get vendor's products
        $products = Product::where('vendor_id', $vendor->id)->get();

        // Calculate Revenue (Total from OrderItems belonging to this vendor)
        $revenue = \App\Models\OrderItem::whereHas('product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->sum('total');

        // Calculate Reviews Count
        $reviewsCount = \App\Models\Review::whereHas('product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->count();

        // Get orders containing vendor's products
        $recentOrders = Order::whereHas('orderItems.product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->with(['user', 'orderItems' => function($q) use ($vendor) {
             $q->whereHas('product', function($sq) use ($vendor) {
                 $sq->where('vendor_id', $vendor->id);
             });
        }])->latest()->take(5)->get();
        
        // Data for Revenue Chart (Last 7 days)
        $today = now();
        $revenueData = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $labels[] = $date->format('d/m');
            
            $dailyRevenue = \App\Models\OrderItem::whereHas('product', function ($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })
            ->whereDate('created_at', $date)
            ->sum('total');
            
            $revenueData[] = $dailyRevenue;
        }

        return view('vendor.dashboard', [
            'vendor' => $vendor, // Pass vendor for name display
            'productsCount' => $products->count(),
            'lowStockCount' => $products->where('stock_quantity', '<', 10)->count(),
            'ordersCount' => Order::whereHas('orderItems.product', function ($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })->count(),
            'recentOrders' => $recentOrders,
            'totalRevenue' => $revenue,
            'reviewsCount' => $reviewsCount,
            'chartLabels' => $labels,
            'chartData' => $revenueData,
        ]);
    }
}
