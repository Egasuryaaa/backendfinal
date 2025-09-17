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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_toko')->nullable()->after('name');
            $table->text('alamat')->nullable()->after('email');
            $table->string('kota')->nullable()->after('alamat');
            $table->string('provinsi')->nullable()->after('kota');
            $table->text('deskripsi')->nullable()->after('provinsi');
            $table->time('jam_buka')->nullable()->after('deskripsi');
            $table->time('jam_tutup')->nullable()->after('jam_buka');
            $table->boolean('active')->default(true)->after('jam_tutup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nama_toko',
                'alamat',
                'kota',
                'provinsi',
                'deskripsi',
                'jam_buka',
                'jam_tutup',
                'active'
            ]);
        });
    }
};
