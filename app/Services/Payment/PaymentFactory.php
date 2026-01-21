<?php

namespace App\Services\Payment;

use Exception;

class PaymentFactory
{
    public function getGateway(string $paymentMethod): PaymentGatewayInterface
    {
        return match ($paymentMethod) {
            'cod' => new CodGateway(),
            'vnpay' => new VnpayGateway(),
            // 'momo' => new MomoGateway(), // Dễ dàng mở rộng sau này
            // 'stripe' => new StripeGateway(),
            default => throw new Exception("Payment method '{$paymentMethod}' is not supported."),
        };
    }
}