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
        Schema::create('detail_penilaian_studi_kasus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penilaian_studi_kasus_id');
            $table->unsignedBigInteger('aspek_penilaian_studi_kasus_id');
            $table->integer('level_terpilih')->comment('Level yang dipilih reviewer: 0, 1, 2, atau 3');
            $table->timestamps();
            
            $table->foreign('penilaian_studi_kasus_id', 'detail_penilaian_fk')
                ->references('id')
                ->on('penilaian_studi_kasus')
                ->onDelete('cascade');
            
            $table->foreign('aspek_penilaian_studi_kasus_id', 'detail_aspek_fk')
                ->references('id')
                ->on('aspek_penilaian_studi_kasus')
                ->onDelete('cascade');
            
            $table->unique(['penilaian_studi_kasus_id', 'aspek_penilaian_studi_kasus_id'], 'penilaian_aspek_unique');
            $table->index('level_terpilih');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penilaian_studi_kasus');
    }
};
