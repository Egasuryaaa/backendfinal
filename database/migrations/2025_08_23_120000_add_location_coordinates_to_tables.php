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
        // Add coordinates to seller_locations table only if they don't exist
        Schema::table('seller_locations', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_locations', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('kode_pos');
            }
            if (!Schema::hasColumn('seller_locations', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });

        // Add meeting location to appointments table only if it doesn't exist
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'meeting_location')) {
                $table->json('meeting_location')->nullable()->after('catatan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_locations', function (Blueprint $table) {
            if (Schema::hasColumn('seller_locations', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('seller_locations', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });

        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'meeting_location')) {
                $table->dropColumn('meeting_location');
            }
        });
    }
};
