<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Danh sách đơn hàng
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        // Admin và Staff xem tất cả, Customer và Vendor xem theo scope
        $orders = Order::with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Xem chi tiết đơn hàng
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('orderItems.product', 'histories');
        return view('orders.show', compact('order'));
    }

    // API endpoints cho admin/staff/vendor
    public function apiIndex()
    {
        $this->authorize('viewAny', Order::class);

        $orders = Order::with('user')->paginate(10);
        return response()->json($orders);
    }

    public function apiShow(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('orderItems.product', 'histories');
        return response()->json($order);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $validated = $request->validate([
            'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        // Log history
        $order->histories()->create([
            'status' => $validated['order_status'],
            'note' => $request->note ?? 'Status updated by ' . Auth::user()->name,
        ]);

        return response()->json($order);
    }
}