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
        // Drop tabel lama jika ada
        Schema::dropIfExists('assessment_participant');
        
        // Buat tabel baru dengan struktur yang benar
        Schema::create('assessment_participant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_penilaian_id')->constrained('sesi_penilaian')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('peserta')->onDelete('cascade');
            $table->enum('status', ['aktif', 'nonaktif', 'selesai'])->default('aktif');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('durasi_menit')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi
            $table->unique(['sesi_penilaian_id', 'peserta_id'], 'assessment_participant_unique');
            
            // Index untuk performa query
            $table->index(['peserta_id', 'status']);
            $table->index(['sesi_penilaian_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_participant');
    }
};
