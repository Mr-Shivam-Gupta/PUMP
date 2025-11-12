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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // 🔹 Foreign Keys
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();

            // 🔹 Core Payment Info
            $table->string('transaction_id')->unique()->nullable(); // Gateway txn ID
            $table->string('order_id')->nullable(); // Razorpay Order ID / Stripe Session ID
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_refunded', 10, 2)->default(0);
            $table->enum('currency', ['INR', 'USD', 'EUR'])->default('INR');

            // 🔹 Payment Method Details
            $table->enum('payment_method', ['razorpay', 'stripe', 'paypal', 'manual'])->default('razorpay');
            $table->string('payment_channel')->nullable(); // e.g., card, netbanking, wallet, upi
            $table->string('bank_name')->nullable();
            $table->string('card_network')->nullable(); // e.g., Visa, MasterCard
            $table->string('card_last4')->nullable();
            $table->string('upi_id')->nullable();
            $table->string('wallet_name')->nullable();

            // 🔹 Status and Response
            $table->enum('status', ['pending', 'success', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->text('failure_reason')->nullable();
            $table->text('response_data')->nullable(); // full JSON gateway response

            // 🔹 Customer Details (optional but useful)
            $table->string('payer_name')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('payer_contact')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_zip')->nullable();

            // 🔹 Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
