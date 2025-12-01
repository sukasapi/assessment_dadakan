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
        Schema::create('aspek_penilaian_studi_kasus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_studi_kasus_id');
            $table->integer('nomor')->comment('Nomor urut aspek penilaian (1-6 atau lebih)');
            $table->text('pertanyaan')->comment('Pertanyaan/aspek penilaian');
            $table->integer('urutan')->default(0)->comment('Urutan tampil');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            
            $table->foreign('kategori_studi_kasus_id', 'aspek_kategori_fk')
                ->references('id')
                ->on('kategori_studi_kasus')
                ->onDelete('cascade');
            
            $table->index(['kategori_studi_kasus_id', 'nomor']);
            $table->index(['kategori_studi_kasus_id', 'urutan']);
            $table->index('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspek_penilaian_studi_kasus');
    }
};
