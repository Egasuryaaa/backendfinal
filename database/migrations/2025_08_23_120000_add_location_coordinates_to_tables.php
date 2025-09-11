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
        // Add coordinates to seller_locations table
        Schema::table('seller_locations', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('kode_pos');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });

        // Add meeting location to appointments table
        Schema::table('appointments', function (Blueprint $table) {
            $table->json('meeting_location')->nullable()->after('catatan_penjual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_locations', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('meeting_location');
        });
    }
};
