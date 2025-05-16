<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CashPaymentService implements PaymentServiceInterface
{
    public function processPayment(array $data): array
    {
        return $this->recordCashPayment($data);
    }

    public function refundPayment(string $transactionId): array
    {
        // Implement cash refund logic if necessary
        return [
            'status' => 'success',
            'message' => "Cash refund processed for transaction ID: $transactionId"
        ];
    }

    public function getTransactionStatus(string $transactionId): array
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->first();
        
        if ($transaction) {
            return [
                'status' => $transaction->status,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'created_at' => $transaction->created_at,
            ];
        }

        return ['error' => 'Transaction not found'];
    }

    public function recordCashPayment(array $data): array
    {
        $transactionId = Str::uuid()->toString();

        return [
            'status' => 'success',
            'transaction_id' => $transactionId,
            'amount' => $data['amount'],
            'currency' => $data['currency'],
        ];
    }
}
