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
        Schema::create('jawaban_in_tray', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->onDelete('cascade');
            $table->foreignId('penilaian_id')->constrained('penilaian')->onDelete('cascade');
            $table->foreignId('latihan_in_tray_id')->constrained('latihan_in_tray')->onDelete('cascade');
            $table->integer('urutan_prioritas');
            $table->text('disposisi');
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->timestamp('waktu_simpan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_in_tray');
    }
};
