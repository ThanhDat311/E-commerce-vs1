<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

class CardGateway implements PaymentGatewayInterface
{
    public function process(Order $order): array
    {
        // Mock successful card payment initialization
        // In a real implementation, this would interact with Stripe/PayPal API
        return [
            'success' => true,
            'is_redirect' => false,
            'message' => 'Card payment simulated successfully.',
            'redirect_url' => null,
        ];
    }

    public function verify(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'Card payment verification simulated.',
        ];
    }
}
