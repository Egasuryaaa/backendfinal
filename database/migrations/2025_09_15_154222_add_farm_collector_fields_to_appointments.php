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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('fish_farm_id')->nullable()->constrained('fish_farms')->onDelete('set null');
            $table->foreignId('collector_id')->nullable()->constrained('collectors')->onDelete('set null');
            $table->enum('appointment_type', ['penjualan_produk', 'pengepulan_ikan'])->default('penjualan_produk');
            $table->decimal('estimated_weight', 10, 2)->nullable();
            $table->decimal('price_per_kg', 10, 2)->nullable();
            $table->text('whatsapp_summary')->nullable();
            $table->timestamp('whatsapp_sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['fish_farm_id']);
            $table->dropForeign(['collector_id']);
            $table->dropColumn([
                'fish_farm_id',
                'collector_id',
                'appointment_type',
                'estimated_weight',
                'price_per_kg',
                'whatsapp_summary',
                'whatsapp_sent_at'
            ]);
        });
    }
};
