<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case PROCESSING = 'processing';
    case CANCELLED = 'cancelled';
}

enum PaymentGateway: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case CORPORATE_SOLUTION = 'corporate_solution';
    case CASH = 'cash';
    // Add other gateways as needed
}

enum RefundStatus: string
{
    case PENDING = 'pending';
    case SUCCESSFUL = 'successful';
    case FAILED = 'failed';
    case REJECTED = 'rejected';
    case PARTIALLY_REFUNDED = 'partially_refunded';
}

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'donation_id',
        'payment_gateway',
        'transaction_id',
        'status',
        'amount',
        'currency',
        'response_data',
        'refund_id',
        'refund_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_gateway' => PaymentGateway::class,
        'status' => TransactionStatus::class,
        'refund_status' => RefundStatus::class,
        'response_data' => 'json',
    ];

    /**
     * Get the donation associated with the transaction.
     *
     * @return BelongsTo
     */
    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }
}