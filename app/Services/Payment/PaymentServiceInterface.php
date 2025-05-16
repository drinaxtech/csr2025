<?php

namespace App\Services\Payment;

interface PaymentServiceInterface
{
    public function processPayment(array $data): array;
    public function refundPayment(string $transactionId): array;
    public function getTransactionStatus(string $transactionId): array;
    public function recordCashPayment(array $data): array; 
}
