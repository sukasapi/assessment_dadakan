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
        Schema::table('kemajuan_penilaian', function (Blueprint $table) {
            $table->text('jawaban')->nullable()->after('aktivitas_terakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemajuan_penilaian', function (Blueprint $table) {
            $table->dropColumn('jawaban');
        });
    }
};
