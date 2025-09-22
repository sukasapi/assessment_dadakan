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
        Schema::create('prioritas_memo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jawaban_in_tray_id')->constrained('jawaban_in_tray')->onDelete('cascade');
            $table->enum('kategori_prioritas', [
                'mendesak_penting',
                'mendesak_tidak_penting', 
                'tidak_mendesak_penting',
                'tidak_mendesak_tidak_penting'
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prioritas_memo');
    }
};
