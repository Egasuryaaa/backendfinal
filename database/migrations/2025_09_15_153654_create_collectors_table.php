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
        Schema::create('collectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->text('deskripsi');
            $table->json('lokasi_koordinat')->nullable(); // {lat: xx, lng: xx}
            $table->decimal('rate_harga_per_kg', 10, 2); // harga per kg dalam rupiah
            $table->string('no_telepon', 20);
            $table->text('alamat');
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->string('foto')->nullable();
            $table->decimal('kapasitas_maksimal', 10, 2)->nullable(); // kg per hari
            $table->json('jenis_ikan_diterima')->nullable(); // array jenis ikan
            $table->time('jam_operasional_mulai')->nullable();
            $table->time('jam_operasional_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectors');
    }
};
