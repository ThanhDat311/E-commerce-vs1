<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of vendor's orders only
     */
    public function index(Request $request)
    {
        $vendor = Auth::user();

        // Get orders containing vendor's products
        $query = Order::whereHas('orderItems.product', function ($productQuery) use ($vendor) {
            $productQuery->where('vendor_id', $vendor->id);
        })->with(['user', 'orderItems.product']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $vendor = Auth::user();

        $order = Order::with(['user', 'orderItems.product'])
            ->findOrFail($id);

        // Ensure vendor can only see orders containing their products
        $hasVendorProduct = $order->orderItems->some(function ($item) use ($vendor) {
            return $item->product->vendor_id === $vendor->id;
        });

        if (!$hasVendorProduct) {
            abort(403, 'Unauthorized to view this order');
        }

        $histories = $order->histories()->orderBy('created_at', 'desc')->get();

        return view('vendor.orders.show', compact('order', 'histories'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $vendor = Auth::user();

        $order = Order::with('orderItems.product')->findOrFail($id);

        // Ensure vendor can only update orders containing their products
        $hasVendorProduct = $order->orderItems->some(function ($item) use ($vendor) {
            return $item->product->vendor_id === $vendor->id;
        });

        if (!$hasVendorProduct) {
            abort(403, 'Unauthorized to update this order');
        }

        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,completed,cancelled,shipped'
        ]);

        // Record the status change in history
        OrderHistory::create([
            'order_id' => $order->id,
            'old_status' => $order->order_status,
            'new_status' => $validated['order_status'],
            'action_by' => $vendor->id,
            'notes' => $request->input('notes', 'Status updated by vendor')
        ]);

        $order->update(['order_status' => $validated['order_status']]);

        return redirect()->route('vendor.orders.show', $order->id)
            ->with('success', 'Order status updated successfully.');
    }
}
