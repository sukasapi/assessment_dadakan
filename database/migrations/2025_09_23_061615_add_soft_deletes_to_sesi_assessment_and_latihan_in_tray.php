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
        // Add soft delete to sesi_assessment table (jika belum ada)
        if (Schema::hasTable('sesi_assessment') && !Schema::hasColumn('sesi_assessment', 'deleted_at')) {
            Schema::table('sesi_assessment', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add soft delete to latihan_in_tray table (jika belum ada)
        if (Schema::hasTable('latihan_in_tray') && !Schema::hasColumn('latihan_in_tray', 'deleted_at')) {
            Schema::table('latihan_in_tray', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove soft delete from sesi_assessment table (jika ada)
        if (Schema::hasTable('sesi_assessment') && Schema::hasColumn('sesi_assessment', 'deleted_at')) {
            Schema::table('sesi_assessment', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // Remove soft delete from latihan_in_tray table (jika ada)
        if (Schema::hasTable('latihan_in_tray') && Schema::hasColumn('latihan_in_tray', 'deleted_at')) {
            Schema::table('latihan_in_tray', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
