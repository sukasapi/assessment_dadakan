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
            $table->unsignedBigInteger('sesi_penilaian_id')->nullable()->after('penilaian_id');
            $table->foreign('sesi_penilaian_id')->references('id')->on('sesi_penilaian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemajuan_penilaian', function (Blueprint $table) {
            $table->dropForeign(['sesi_penilaian_id']);
            $table->dropColumn('sesi_penilaian_id');
        });
    }
};
