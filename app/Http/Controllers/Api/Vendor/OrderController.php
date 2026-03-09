<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated listing of vendor's orders.
     * Filterable by: status, date_from, date_to
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $vendor = Auth::user();

        $query = Order::withoutGlobalScopes()
            ->whereHas('orderItems.product', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->with(['user', 'orderItems.product']);

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return OrderResource::collection($orders);
    }

    /**
     * Display the specified order (accessible only if vendor has a product in it).
     */
    public function show(int $orderId): JsonResponse
    {
        $vendor = Auth::user();

        $order = Order::withoutGlobalScopes()
            ->with(['user', 'orderItems.product'])
            ->findOrFail($orderId);

        $this->authorizeVendorOrder($order, $vendor->id);

        $order->load('histories');

        return $this->successResponse(new OrderResource($order), 'Order retrieved successfully.');
    }

    /**
     * Update order status (vendor can set: processing, shipped, completed).
     */
    public function updateStatus(Request $request, int $orderId): JsonResponse
    {
        $vendor = Auth::user();

        $order = Order::withoutGlobalScopes()
            ->with('orderItems.product')
            ->findOrFail($orderId);

        $this->authorizeVendorOrder($order, $vendor->id);

        $validated = $request->validate([
            'order_status' => 'required|in:processing,shipped,completed',
            'notes' => 'nullable|string|max:500',
        ]);

        OrderHistory::create([
            'order_id' => $order->id,
            'user_id' => $vendor->id,
            'action' => 'Status changed from '.$order->order_status.' to '.$validated['order_status'],
            'description' => $validated['notes'] ?? 'Status updated by vendor via API',
        ]);

        $order->update(['order_status' => $validated['order_status']]);

        return $this->successResponse([
            'id' => $order->id,
            'order_status' => $order->order_status,
        ], 'Order status updated successfully.');
    }

    /**
     * Verify that the given order contains at least one product belonging to this vendor.
     */
    private function authorizeVendorOrder(Order $order, int $vendorId): void
    {
        $hasVendorProduct = $order->orderItems->some(
            fn ($item) => $item->product?->vendor_id === $vendorId
        );

        if (! $hasVendorProduct) {
            abort(403, 'You are not authorized to access this order.');
        }
    }
}
