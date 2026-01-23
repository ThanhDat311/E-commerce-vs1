<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    /**
     * Get user's order history
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();

        $orders = $user->orders()
            ->with('items.product')
            ->latest()
            ->paginate(15);

        return $this->paginatedResponse(
            $orders,
            'Order history retrieved successfully'
        );
    }

    /**
     * Get order details
     *
     * @param Request $request
     * @param int $orderId
     * @return JsonResponse
     */
    public function detail(Request $request, int $orderId): JsonResponse
    {
        $user = $request->user();

        $order = $user->orders()
            ->with(['items.product', 'address'])
            ->findOrFail($orderId);

        $orderData = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'total' => $order->total,
            'subtotal' => $order->subtotal,
            'tax' => $order->tax,
            'shipping_cost' => $order->shipping_cost,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'notes' => $order->notes,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'items' => $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_image' => $item->product->image,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                ];
            }),
        ];

        if ($order->address) {
            $orderData['shipping_address'] = [
                'street' => $order->address->street,
                'city' => $order->address->city,
                'state' => $order->address->state,
                'postal_code' => $order->address->postal_code,
                'country' => $order->address->country,
            ];
        }

        return $this->successResponse($orderData, 'Order details retrieved successfully');
    }

    /**
     * Get order summary (brief)
     *
     * @param Request $request
     * @param int $orderId
     * @return JsonResponse
     */
    public function summary(Request $request, int $orderId): JsonResponse
    {
        $user = $request->user();

        $order = $user->orders()->findOrFail($orderId);

        return $this->successResponse([
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'total' => $order->total,
            'payment_status' => $order->payment_status,
            'created_at' => $order->created_at,
        ], 'Order summary retrieved successfully');
    }

    /**
     * Cancel order
     *
     * @param Request $request
     * @param int $orderId
     * @return JsonResponse
     */
    public function cancel(Request $request, int $orderId): JsonResponse
    {
        $user = $request->user();

        $order = $user->orders()->findOrFail($orderId);

        // Only allow cancelling orders with certain statuses
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return $this->errorResponse('Order cannot be cancelled in current status', 400);
        }

        $order->update(['status' => 'cancelled']);

        return $this->successResponse([
            'id' => $order->id,
            'status' => 'cancelled',
        ], 'Order cancelled successfully');
    }

    /**
     * Track order
     *
     * @param Request $request
     * @param int $orderId
     * @return JsonResponse
     */
    public function track(Request $request, int $orderId): JsonResponse
    {
        $user = $request->user();

        $order = $user->orders()->findOrFail($orderId);

        $tracking = [
            'order_number' => $order->order_number,
            'current_status' => $order->status,
            'timeline' => [],
        ];

        // Build timeline from order history
        $history = $order->history()
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($history as $entry) {
            $tracking['timeline'][] = [
                'status' => $entry->status,
                'timestamp' => $entry->created_at,
                'notes' => $entry->notes,
            ];
        }

        return $this->successResponse($tracking, 'Order tracking retrieved successfully');
    }
}
