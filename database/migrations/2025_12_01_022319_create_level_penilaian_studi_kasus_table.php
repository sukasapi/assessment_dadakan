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
        if (Schema::hasTable('level_penilaian_studi_kasus')) {
            return;
        }
        
        Schema::create('level_penilaian_studi_kasus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aspek_penilaian_studi_kasus_id');
            $table->integer('level')->comment('Level penilaian: 0, 1, 2, atau 3');
            $table->text('deskripsi_level')->comment('Deskripsi level penilaian');
            $table->text('text_report')->comment('Text yang akan muncul di report sesuai level yang terpilih');
            $table->timestamps();
            
            $table->foreign('aspek_penilaian_studi_kasus_id', 'level_aspek_fk')
                ->references('id')
                ->on('aspek_penilaian_studi_kasus')
                ->onDelete('cascade');
            
            $table->unique(['aspek_penilaian_studi_kasus_id', 'level'], 'aspek_level_unique');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_penilaian_studi_kasus');
    }
};
