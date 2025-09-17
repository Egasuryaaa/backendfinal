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
        Schema::create('fish_farms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->integer('banyak_bibit');
            $table->json('lokasi_koordinat')->nullable(); // {lat: xx, lng: xx}
            $table->text('alamat');
            $table->string('jenis_ikan');
            $table->decimal('luas_tambak', 10, 2); // dalam meter persegi
            $table->string('foto')->nullable();
            $table->string('no_telepon', 20);
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fish_farms');
    }
};


