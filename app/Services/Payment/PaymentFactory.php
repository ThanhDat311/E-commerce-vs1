<?php

namespace App\Services\Payment;

use Exception;

class PaymentFactory
{
    /**
     * Payment methods that are not fully implemented.
     * These are blocked in non-local environments to prevent
     * orders from completing without actual payment verification.
     */
    private const STUB_GATEWAYS = ['card', 'transfer'];

    public function getGateway(string $paymentMethod): PaymentGatewayInterface
    {
        if (! app()->isLocal() && in_array($paymentMethod, self::STUB_GATEWAYS, true)) {
            throw new Exception("Payment method '{$paymentMethod}' is not available in this environment.");
        }

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
