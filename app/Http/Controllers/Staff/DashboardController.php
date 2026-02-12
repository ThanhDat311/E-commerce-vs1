<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    protected $orderRepo;

    protected $productRepo;

    public function __construct(
        \App\Repositories\Interfaces\OrderRepositoryInterface $orderRepo,
        \App\Repositories\Interfaces\ProductRepositoryInterface $productRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        // 1. Operational Metrics
        // Orders to Ship: Paid but not shipped (processing)
        $ordersToShip = Order::where('order_status', 'processing')->count();

        // Low Stock Alerts
        $lowStockCount = $this->productRepo->getLowStockCount(10);

        // Today's New Orders
        $todayOrders = Order::whereDate('created_at', today())->count();

        // Pending Returns/Refunds (Placeholder or actual if exists)
        // Check for 'cancelled' or specific status.
        // Or unread messages if available.
        // Let's use 'cancelled' orders that might need refund processing as a proxy for now
        $pendingReturns = Order::where('order_status', 'cancelled')->where('payment_status', 'paid')->count();

        // 2. Action Required Orders
        // Orders needing attention: pending or processing
        $actionRequiredOrders = Order::whereIn('order_status', ['pending', 'processing'])
            ->orderBy('created_at', 'asc') // Oldest first to prioritize
            ->limit(10)
            ->get();

        return view('pages.staff.dashboard', compact(
            'ordersToShip',
            'lowStockCount',
            'todayOrders',
            'pendingReturns',
            'actionRequiredOrders'
        ));
    }
}
