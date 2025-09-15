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
        Schema::table('latihan_in_tray', function (Blueprint $table) {
            // Hapus foreign key dan index lama
            $table->dropForeign(['sesi_assessment_id']);
            $table->dropIndex(['sesi_assessment_id', 'aktif']);
            
            // Hapus kolom lama
            $table->dropColumn('sesi_assessment_id');
            
            // Tambah kolom baru
            $table->foreignId('sesi_penilaian_id')->nullable()->after('penilaian_id')->constrained('sesi_penilaian')->onDelete('cascade');
            $table->index(['sesi_penilaian_id', 'aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('latihan_in_tray', function (Blueprint $table) {
            // Hapus foreign key dan index baru
            $table->dropForeign(['sesi_penilaian_id']);
            $table->dropIndex(['sesi_penilaian_id', 'aktif']);
            
            // Hapus kolom baru
            $table->dropColumn('sesi_penilaian_id');
            
            // Kembalikan kolom lama
            $table->foreignId('sesi_assessment_id')->nullable()->after('penilaian_id')->constrained('sesi_assessment')->onDelete('cascade');
            $table->index(['sesi_assessment_id', 'aktif']);
        });
    }
};
