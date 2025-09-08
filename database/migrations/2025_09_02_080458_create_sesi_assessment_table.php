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
        Schema::create('sesi_assessment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_penilaian_id')->constrained('sesi_penilaian')->onDelete('cascade');
            $table->foreignId('penilaian_id')->constrained('penilaian')->onDelete('cascade');
            $table->integer('urutan')->default(1); // Urutan assessment dalam sesi
            $table->boolean('aktif')->default(true); // Apakah assessment ini aktif dalam sesi
            $table->integer('durasi_default')->nullable(); // Durasi default dalam menit
            $table->text('instruksi_khusus')->nullable(); // Instruksi khusus untuk assessment ini
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi assessment dalam sesi
            $table->unique(['sesi_penilaian_id', 'penilaian_id'], 'sesi_assessment_unique');
            
            // Index untuk performa query
            $table->index(['sesi_penilaian_id', 'aktif']);
            $table->index(['penilaian_id', 'aktif']);
            $table->index(['urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_assessment');
    }
};
