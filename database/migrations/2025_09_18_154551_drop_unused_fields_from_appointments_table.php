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
            // Drop foreign key constraints first
            $table->dropForeign(['pembeli_id']);
            $table->dropForeign(['penjual_id']);
            $table->dropForeign(['lokasi_penjual_id']);
            
            // Then drop the columns
            $table->dropColumn([
                'pembeli_id',
                'penjual_id', 
                'lokasi_penjual_id',
                'meeting_location'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Restore columns if needed (for rollback)
            $table->unsignedBigInteger('pembeli_id')->nullable();
            $table->unsignedBigInteger('penjual_id')->nullable();
            $table->unsignedBigInteger('lokasi_penjual_id')->nullable();
            $table->json('meeting_location')->nullable();
            
            // Restore foreign keys
            $table->foreign('pembeli_id')->references('id')->on('users');
            $table->foreign('penjual_id')->references('id')->on('users');
            $table->foreign('lokasi_penjual_id')->references('id')->on('seller_locations');
        });
    }
};
