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
        if (Schema::hasTable('sesi_assessment') && !Schema::hasColumn('sesi_assessment', 'model_in_tray')) {
            Schema::table('sesi_assessment', function (Blueprint $table) {
                $table->enum('model_in_tray', ['urutan', 'prioritas'])->default('urutan')->after('instruksi_khusus');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sesi_assessment') && Schema::hasColumn('sesi_assessment', 'model_in_tray')) {
            Schema::table('sesi_assessment', function (Blueprint $table) {
                $table->dropColumn('model_in_tray');
            });
        }
    }
};