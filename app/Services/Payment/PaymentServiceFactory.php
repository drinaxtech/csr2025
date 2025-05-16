<?php

namespace App\Services\Payment;
use App\Services\Payment\StripePaymentService;
use App\Services\Payment\PayPalPaymentService;
use App\Services\Payment\CashPaymentService;
use App\Models\PaymentGateway;

class PaymentServiceFactory
{
    public static function make(): PaymentServiceInterface
    {
        $provider = config('payment.default');

        switch ($provider) {
            case 'stripe':
                return app(StripePaymentService::class);
            case 'paypal':
                return app(PayPalPaymentService::class);
            case 'cash':
                return app(CashPaymentService::class);
            default:
                throw new \Exception("Unsupported payment provider: {$provider}");
        }
    }
}