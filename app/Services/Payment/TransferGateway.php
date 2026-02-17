<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

class TransferGateway implements PaymentGatewayInterface
{
    public function process(Order $order): array
    {
        // Bank Transfer logic (similar to COD, instructions provided later)
        return [
            'success' => true,
            'is_redirect' => false,
            'message' => 'Please transfer the total amount to our bank account. Order ID: '.$order->id,
            'redirect_url' => null,
        ];
    }

    public function verify(Request $request): array
    {
        return [
            'success' => false,
            'message' => 'Bank transfer requires manual verification.',
        ];
    }
}
