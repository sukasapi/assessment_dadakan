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
        Schema::create('kategori_studi_kasus', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique()->comment('Kode kategori: PQ atau BQ');
            $table->string('nama', 100)->comment('Nama kategori');
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            
            $table->index('kode');
            $table->index('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_studi_kasus');
    }
};
