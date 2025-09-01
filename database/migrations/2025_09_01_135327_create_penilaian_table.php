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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_penilaian_id')->constrained('sesi_penilaian')->onDelete('cascade');
            $table->string('nama');
            $table->enum('jenis', ['studi_kasus', 'in_tray', 'roleplay', 'fgd']);
            $table->text('petunjuk')->nullable();
            $table->text('konten')->nullable();
            $table->integer('durasi_menit')->default(60);
            $table->integer('urutan')->default(0); // Urutan step dalam stepper
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
