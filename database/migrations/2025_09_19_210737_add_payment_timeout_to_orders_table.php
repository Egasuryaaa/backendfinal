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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('payment_deadline')->nullable()->after('catatan');
            $table->string('payment_proof')->nullable()->after('payment_deadline');
            $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_deadline', 'payment_proof', 'payment_proof_uploaded_at']);
        });
    }
};
