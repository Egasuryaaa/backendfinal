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
            // Add user_id column for pemilik_tambak relationship
            if (!Schema::hasColumn('appointments', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            }
            
            // Make lokasi_penjual_id nullable for new system
            $table->unsignedBigInteger('lokasi_penjual_id')->nullable()->change();
            
            // Add other missing columns if they don't exist
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
            // Drop user_id column that was added
            if (Schema::hasColumn('appointments', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
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
