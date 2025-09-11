<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus data duplikat terlebih dahulu
        DB::statement('DELETE k1 FROM kemajuan_penilaian k1 INNER JOIN kemajuan_penilaian k2 WHERE k1.id > k2.id AND k1.peserta_id = k2.peserta_id AND k1.penilaian_id = k2.penilaian_id');
        
        // Tambahkan constraint baru dengan sesi_penilaian_id
        Schema::table('kemajuan_penilaian', function (Blueprint $table) {
            $table->unique(['peserta_id', 'penilaian_id', 'sesi_penilaian_id'], 'kemajuan_penilaian_session_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemajuan_penilaian', function (Blueprint $table) {
            $table->dropUnique('kemajuan_penilaian_session_unique');
        });
    }
};
