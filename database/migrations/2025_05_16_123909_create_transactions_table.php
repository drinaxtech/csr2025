<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donation_transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('donation_id')
                  ->constrained('donations')
                  ->cascadeOnDelete();
            $table->enum('payment_gateway', ['stripe', 'paypal', 'corporate_solution', 'bank_transfer', 'cash']);
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'processing', 'cancelled']);
            $table->decimal('amount', 10, 2);
            $table->string('currency')->nullable();
            $table->json('response_data')->nullable();
            $table->string('refund_id')->nullable();
            $table->enum('refund_status', ['pending', 'successful', 'failed', 'rejected', 'partially_refunded'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};