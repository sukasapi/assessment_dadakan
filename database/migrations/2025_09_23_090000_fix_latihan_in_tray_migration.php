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
        // Migration ini untuk memperbaiki masalah migration sebelumnya
        // yang gagal karena mencoba menghapus foreign key yang tidak ada
        
        // Cek apakah kolom sesi_penilaian_id sudah ada
        if (!Schema::hasColumn('latihan_in_tray', 'sesi_penilaian_id')) {
            // Jika belum ada, tambahkan
            Schema::table('latihan_in_tray', function (Blueprint $table) {
                $table->unsignedBigInteger('sesi_penilaian_id')->nullable()->after('penilaian_id');
                $table->index(['sesi_penilaian_id', 'aktif']);
            });
        }
        
        // Cek apakah kolom sesi_assessment_id masih ada, jika ya hapus
        if (Schema::hasColumn('latihan_in_tray', 'sesi_assessment_id')) {
            Schema::table('latihan_in_tray', function (Blueprint $table) {
                // Coba hapus foreign key jika ada
                try {
                    $table->dropForeign(['sesi_assessment_id']);
                } catch (Exception $e) {
                    // Foreign key tidak ada, lanjutkan
                }
                
                // Coba hapus index jika ada
                try {
                    $table->dropIndex(['sesi_assessment_id', 'aktif']);
                } catch (Exception $e) {
                    // Index tidak ada, lanjutkan
                }
                
                // Hapus kolom
                $table->dropColumn('sesi_assessment_id');
            });
        }
        
        // Ubah tipe data sesi_penilaian_id menjadi unsigned jika belum
        if (Schema::hasColumn('latihan_in_tray', 'sesi_penilaian_id')) {
            Schema::table('latihan_in_tray', function (Blueprint $table) {
                $table->unsignedBigInteger('sesi_penilaian_id')->nullable()->change();
            });
        }
        
        // Pastikan foreign key untuk sesi_penilaian_id ada
        if (Schema::hasColumn('latihan_in_tray', 'sesi_penilaian_id')) {
            // Cek apakah foreign key sudah ada
            $foreignKeys = DB::select("
                SELECT 
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE 
                    TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'latihan_in_tray' 
                    AND COLUMN_NAME = 'sesi_penilaian_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (empty($foreignKeys)) {
                Schema::table('latihan_in_tray', function (Blueprint $table) {
                    $table->foreign('sesi_penilaian_id')->references('id')->on('sesi_penilaian')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini adalah perbaikan
    }
};