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

        // Get orders containing vendor's products
        $orders = Order::whereHas('orderItems.product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->latest()->take(5)->get();

        return view('vendor.dashboard', [
            'productsCount' => $products->count(),
            'lowStockCount' => $products->where('stock_quantity', '<', 10)->count(),
            'ordersCount' => Order::whereHas('orderItems.product', function ($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })->count(),
            'recentOrders' => $orders,
        ]);
    }
}
