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
            // Add only missing columns based on existing structure
            if (!Schema::hasColumn('appointments', 'penjual_id')) {
                $table->foreignId('penjual_id')->nullable()->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('appointments', 'pembeli_id')) {
                $table->foreignId('pembeli_id')->nullable()->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('appointments', 'lokasi_penjual_id')) {
                $table->foreignId('lokasi_penjual_id')->nullable()->constrained('seller_locations')->onDelete('cascade');
            }
            if (!Schema::hasColumn('appointments', 'waktu_janji')) {
                $table->string('waktu_janji')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'tujuan')) {
                $table->string('tujuan')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'pesan_pemilik')) {
                $table->text('pesan_pemilik')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop only columns that were added
            if (Schema::hasColumn('appointments', 'penjual_id')) {
                $table->dropForeign(['penjual_id']);
                $table->dropColumn('penjual_id');
            }
            if (Schema::hasColumn('appointments', 'pembeli_id')) {
                $table->dropForeign(['pembeli_id']);
                $table->dropColumn('pembeli_id');
            }
            if (Schema::hasColumn('appointments', 'lokasi_penjual_id')) {
                $table->dropForeign(['lokasi_penjual_id']);
                $table->dropColumn('lokasi_penjual_id');
            }
            if (Schema::hasColumn('appointments', 'waktu_janji')) {
                $table->dropColumn('waktu_janji');
            }
            if (Schema::hasColumn('appointments', 'tujuan')) {
                $table->dropColumn('tujuan');
            }
            if (Schema::hasColumn('appointments', 'pesan_pemilik')) {
                $table->dropColumn('pesan_pemilik');
            }
        });
    }
};
