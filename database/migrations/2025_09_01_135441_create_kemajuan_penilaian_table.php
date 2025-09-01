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
        Schema::create('kemajuan_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->onDelete('cascade');
            $table->foreignId('penilaian_id')->constrained('penilaian')->onDelete('cascade');
            $table->enum('status', ['belum_mulai', 'sedang_berlangsung', 'selesai'])->default('belum_mulai');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamp('aktivitas_terakhir')->nullable();
            $table->timestamps();
            
            $table->unique(['peserta_id', 'penilaian_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kemajuan_penilaian');
    }
};
