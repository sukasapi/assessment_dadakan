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
        Schema::table('penilaian_studi_kasus', function (Blueprint $table) {
            if (!Schema::hasColumn('penilaian_studi_kasus', 'kategori_studi_kasus_id')) {
                $table->unsignedBigInteger('kategori_studi_kasus_id')->nullable()->after('user_id');
                $table->foreign('kategori_studi_kasus_id', 'penilaian_kategori_fk')
                    ->references('id')
                    ->on('kategori_studi_kasus')
                    ->onDelete('cascade');
                $table->index('kategori_studi_kasus_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_studi_kasus', function (Blueprint $table) {
            $table->dropForeign(['kategori_studi_kasus_id']);
            $table->dropIndex(['kategori_studi_kasus_id']);
            $table->dropColumn('kategori_studi_kasus_id');
        });
    }
};
