<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderRepaymentController extends Controller
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
        try {
            $userId = Auth::id();

            // Call Service to process repayment
            $result = $this->orderService->repayOrder($id, $userId);

            // If gateway requires redirect (e.g., VNPay)
            if (isset($result['is_redirect']) && $result['is_redirect'] && ! empty($result['redirect_url'])) {
                return redirect()->to($result['redirect_url']);
            }

            // For COD or direct payments
            return redirect()->route('orders.show', $id)
                ->with('success', 'Payment processed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to process payment: '.$e->getMessage());
        }
    }
}
