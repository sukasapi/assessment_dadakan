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
        Schema::table('jawaban_in_tray', function (Blueprint $table) {
            $table->enum('model_assessment', ['urutan', 'prioritas'])->default('urutan');
            $table->text('pertanyaan')->nullable();
            $table->text('jawaban_pertanyaan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban_in_tray', function (Blueprint $table) {
            $table->dropColumn(['model_assessment', 'pertanyaan', 'jawaban_pertanyaan']);
        });
    }
};
