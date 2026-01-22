<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderCancellationController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        // Validate lý do hủy (Optional)
        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            $userId = Auth::id();
            
            // Gọi Service để xử lý logic
            $this->orderService->cancelOrder($id, $userId, $request->input('reason'));

            // Redirect về trang chi tiết đơn hàng với thông báo thành công
            return redirect()->route('orders.show', $id)
                ->with('success', 'Order has been cancelled successfully.');

        } catch (\Exception $e) {
            // Redirect lại với thông báo lỗi
            return redirect()->back()
                ->with('error', 'Unable to cancel order: ' . $e->getMessage());
        }
    }
}