<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CartService $cartService,
    ) {}

    public function show()
    {
        $cartData = $this->cartService->getCartDetails();
        $addresses = Auth::check() ? Auth::user()->addresses : collect();

        // Check if cart is empty
        if (empty($cartData['cartItems'])) {
            return redirect()->route('shop.index');
        }

        return view('checkout', array_merge($cartData, ['addresses' => $addresses]));
    }

    public function process(CheckoutRequest $request)
    {
        try {
            $customerData = $request->validated();
            $userId = Auth::id();

            $result = $this->orderService->processCheckout($customerData, $userId);
            $order = $result['order'];
            $paymentResult = $result['payment_result'];

            // If payment gateway requires redirect (e.g. VNPay)
            if ($paymentResult['is_redirect'] && ! empty($paymentResult['redirect_url'])) {
                return redirect()->to($paymentResult['redirect_url']);
            }

            // COD or local payment
            return redirect()->route('checkout.success')->with('order_id', $order->id);
        } catch (Exception $e) {
            Log::error('Checkout process failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'input' => $request->except(['_token']),
            ]);

            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function success()
    {
        if (! session('order_id')) {
            return redirect()->route('home');
        }

        return view('order-success');
    }
}
