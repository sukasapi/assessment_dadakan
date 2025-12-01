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
        // Buat kolom pertanyaan_1, pertanyaan_2, pertanyaan_3 menjadi nullable
        // untuk kompatibilitas dengan sistem baru yang tidak menggunakan kolom ini
        Schema::table('penilaian_studi_kasus', function (Blueprint $table) {
            $table->enum('pertanyaan_1', ['ya', 'tidak'])->nullable()->change();
            $table->enum('pertanyaan_2', ['ya', 'tidak'])->nullable()->change();
            $table->enum('pertanyaan_3', ['ya', 'tidak'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke not null (tapi hanya jika tidak ada data NULL)
        Schema::table('penilaian_studi_kasus', function (Blueprint $table) {
            // Hanya ubah jika tidak ada data NULL
            $hasNull = DB::table('penilaian_studi_kasus')
                ->whereNull('pertanyaan_1')
                ->orWhereNull('pertanyaan_2')
                ->orWhereNull('pertanyaan_3')
                ->exists();
            
            if (!$hasNull) {
                $table->enum('pertanyaan_1', ['ya', 'tidak'])->nullable(false)->change();
                $table->enum('pertanyaan_2', ['ya', 'tidak'])->nullable(false)->change();
                $table->enum('pertanyaan_3', ['ya', 'tidak'])->nullable(false)->change();
            }
        });
    }
};
