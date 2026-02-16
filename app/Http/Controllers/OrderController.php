<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Danh sách đơn hàng
    // Danh sách đơn hàng
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $query = Auth::user()->orders()->with(['orderItems.product']);

        // Filter by Status
        if ($request->has('status') && $request->status !== 'all') {
            // Map frontend tabs to database statuses if needed, or use direct mapping
            // Tabs: all, to_pay, to_ship, to_receive, completed, cancelled
            // DB: pending, confirmed, processing, shipped, delivered, cancelled, refunding, refunded, failed
            $status = $request->status;

            if ($status === 'to_pay') {
                $query->where('payment_status', 'pending')->where('order_status', 'pending');
            } elseif ($status === 'to_ship') {
                $query->whereIn('order_status', ['confirmed', 'processing']);
            } elseif ($status === 'to_receive') {
                $query->where('order_status', 'shipped');
            } elseif ($status === 'completed') {
                $query->where('order_status', 'delivered');
            } elseif ($status === 'cancelled') {
                $query->where('order_status', 'cancelled');
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('orders.id', 'like', "%{$search}%")
                    ->orWhereHas('orderItems.product', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        $user = Auth::user();

        return view('pages.store.orders.index', compact('orders', 'user'));
    }

    // Xem chi tiết đơn hàng
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('orderItems.product', 'histories');
        $order->load('orderItems.product', 'histories');
        $user = Auth::user();

        return view('pages.store.orders.show', compact('order', 'user'));
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
            'note' => $request->note ?? 'Status updated by '.Auth::user()->name,
        ]);

        return response()->json($order);
    }
}
