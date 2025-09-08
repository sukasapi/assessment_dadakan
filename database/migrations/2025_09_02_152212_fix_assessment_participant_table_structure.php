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
        Schema::table('assessment_participant', function (Blueprint $table) {
            // Drop unique constraint yang lama
            $table->dropUnique('assessment_participant_unique');
            
            // Drop foreign key yang lama
            $table->dropForeign(['penilaian_id']);
            $table->dropColumn('penilaian_id');
            
            // Drop index yang lama
            $table->dropIndex(['penilaian_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_participant', function (Blueprint $table) {
            // Tambahkan kembali kolom penilaian_id
            $table->foreignId('penilaian_id')->constrained('penilaian')->onDelete('cascade');
            
            // Tambahkan kembali unique constraint
            $table->unique(['penilaian_id', 'peserta_id', 'sesi_penilaian_id'], 'assessment_participant_unique');
            
            // Tambahkan kembali index
            $table->index(['penilaian_id', 'status']);
        });
    }
};
