<?php

namespace App\Services\Payment;

use Exception;

class PaymentFactory
{
    public function getGateway(string $paymentMethod): PaymentGatewayInterface
    {
        return match ($paymentMethod) {
            'cod' => new CodGateway,
            'vnpay' => new VnpayGateway,
            'card' => new CardGateway,
            'transfer' => new TransferGateway,
            // 'momo' => new MomoGateway(), // Dễ dàng mở rộng sau này
            default => throw new Exception("Payment method '{$paymentMethod}' is not supported."),
        };
    }
}
