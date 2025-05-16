<?php

namespace App\Services\Payment;

use Stripe\Stripe;
use Stripe\Charge;

class StripePaymentService implements PaymentServiceInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function processPayment(array $data): array
    {
        $charge = Charge::create([
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'source' => $data['token'],
            'description' => $data['description'],
        ]);

        return [
            'status' => $charge->status,
            'transaction_id' => $charge->id,
            'amount' => $charge->amount,
        ];
    }

    public function refundPayment(string $transactionId): array
    {
        $refund = \Stripe\Refund::create([
            'charge' => $transactionId,
        ]);

        return [
            'status' => $refund->status,
            'transaction_id' => $refund->id,
        ];
    }

    public function getTransactionStatus(string $transactionId): array
    {
        $charge = Charge::retrieve($transactionId);
        return [
            'status' => $charge->status,
            'amount' => $charge->amount,
        ];
    }

    public function recordCashPayment(array $data): array
    {
        return $data;
    }
}
