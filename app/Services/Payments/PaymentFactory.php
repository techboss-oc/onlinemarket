<?php

namespace App\Services\Payments;

use Exception;

class PaymentFactory
{
    public static function make(string $provider): PaymentServiceInterface
    {
        switch (strtolower($provider)) {
            case 'paystack':
                $service = new PaystackPaymentService();
                $service->configure();
                return $service;
            case 'flutterwave':
                $service = new FlutterwavePaymentService();
                $service->configure();
                return $service;
            default:
                throw new Exception("Unsupported payment provider: {$provider}");
        }
    }
}
