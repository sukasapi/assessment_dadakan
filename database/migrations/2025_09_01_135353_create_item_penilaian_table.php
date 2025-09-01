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
        Schema::create('item_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained('penilaian')->onDelete('cascade');
            $table->string('judul');
            $table->text('konten');
            $table->text('petunjuk')->nullable();
            $table->enum('jenis', ['studi_kasus', 'in_tray', 'roleplay', 'fgd']);
            $table->integer('urutan')->default(0);
            $table->json('opsi')->nullable(); // Untuk in-tray exercise (memo)
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_penilaian');
    }
};
