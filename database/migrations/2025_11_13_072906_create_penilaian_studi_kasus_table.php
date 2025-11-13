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
        Schema::create('penilaian_studi_kasus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jawaban_studi_kasus_id')->constrained('jawaban_studi_kasus')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('peserta')->onDelete('cascade');
            $table->foreignId('penilaian_id')->constrained('penilaian')->onDelete('cascade');
            $table->foreignId('sesi_penilaian_id')->constrained('sesi_penilaian')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Admin yang menilai
            $table->enum('pertanyaan_1', ['ya', 'tidak'])->comment('Apakah jawaban sudah menjawab pertanyaan soal?');
            $table->enum('pertanyaan_2', ['ya', 'tidak'])->comment('Apakah jawaban sudah mencerminkan kompetensi-kompetensi?');
            $table->enum('pertanyaan_3', ['ya', 'tidak'])->comment('Apakah jawaban sudah menggunakan alat analisis?');
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->timestamps();
            
            // Unique constraint: satu jawaban hanya bisa dinilai sekali (tapi bisa diupdate)
            $table->unique('jawaban_studi_kasus_id', 'penilaian_studi_kasus_unique');
            
            // Index untuk performa query
            $table->index(['peserta_id', 'penilaian_id', 'sesi_penilaian_id'], 'penilaian_studi_kasus_composite_idx');
            $table->index('user_id', 'penilaian_studi_kasus_user_idx');
            $table->index('status', 'penilaian_studi_kasus_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_studi_kasus');
    }
};
