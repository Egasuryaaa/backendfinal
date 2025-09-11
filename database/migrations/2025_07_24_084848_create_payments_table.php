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
        //nyoba
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            $table->string('invoice_id')->nullable(); // Xendit Invoice ID
            $table->string('external_id');
            $table->string('order_id');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'expired', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method');
            $table->string('payment_channel');
            $table->text('invoice_url')->nullable(); // URL tampilan pembayaran Xendit
            $table->text('payment_url')->nullable(); // Backup URL field
            $table->string('customer_name')->nullable(); // Customer info
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('description')->nullable(); // Payment description
            $table->json('xendit_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index(['payment_id', 'status']);
            $table->index(['order_id', 'status']);
            $table->index('external_id');
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
