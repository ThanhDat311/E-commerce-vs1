<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Tạo đơn hàng mới
     */
    public function createOrder(array $data)
    {
        return Order::create($data);
    }

    /**
     * Tạo chi tiết đơn hàng (Order Item)
     */
    public function createOrderItem(array $data)
    {
        return OrderItem::create($data);
    }

    /**
     * Lấy danh sách đơn hàng (Phân trang)
     */
    public function getAllOrders($perPage = 10)
    {
        return Order::latest()->paginate($perPage);
    }

    /**
     * Tìm đơn hàng theo ID
     */
    public function find($id)
    {
        return Order::findOrFail($id);
    }

    public function getProductsByVendor($vendorId, $perPage = 10)
    {
        // Placeholder implementation
        return [];
    }

    public function getTotalRevenue($vendorId = null)
    {
        $query = Order::where('order_status', '!=', 'cancelled');

        if ($vendorId) {
            // Assuming orders are linked to vendors via order_items -> product -> vendor_id
            // Or if orders table has vendor_id (unlikely for multi-vendor cart).
            // Usually, for multi-vendor, we calculate revenue based on OrderItems.
            // Let's check OrderItem linkage.
            // If Order has no vendor_id, we must join order_items and products.

            return $query->with('orderItems.product')
                ->get()
                ->sum(function ($order) use ($vendorId) {
                    return $order->orderItems->sum(function ($item) use ($vendorId) {
                        return $item->product->vendor_id == $vendorId ? $item->price * $item->quantity : 0;
                    });
                });

            // OPTIMIZED QUERY:
            // return OrderItem::whereHas('product', function($q) use ($vendorId) {
            //     $q->where('vendor_id', $vendorId);
            // })->whereHas('order', function($q) {
            //     $q->where('order_status', '!=', 'cancelled');
            // })->sum(DB::raw('price * quantity'));
        }

        return $query->sum('total');
    }

    // RE-IMPLEMENTING WITH OPTIMIZED QUERY FOR VENDOR
    public function getVendorRevenue($vendorId)
    {
        return OrderItem::whereHas('product', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->whereHas('order', function ($q) {
            $q->where('order_status', '!=', 'cancelled');
        })->sum(DB::raw('price * quantity'));
    }

    public function count()
    {
        return Order::count();
    }

    public function getPendingCount()
    {
        return Order::where('order_status', 'pending')->count();
    }

    public function getLatestOrders($limit = 5, $vendorId = null)
    {
        if ($vendorId) {
            return Order::whereHas('orderItems.product', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->latest()->limit($limit)->get();
        }

        return Order::latest()->limit($limit)->get();
    }

    public function getRevenueData($days = 7, $vendorId = null)
    {
        if ($vendorId) {
            // Complex query for vendor daily revenue
            return OrderItem::select(
                DB::raw('DATE(order_items.created_at) as date'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total')
            )
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.vendor_id', $vendorId)
                ->where('orders.order_status', '!=', 'cancelled')
                ->where('order_items.created_at', '>=', Carbon::now()->subDays($days))
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();
        }

        return Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total')
        )
            ->where('order_status', '!=', 'cancelled')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
    }
}
