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
        // Disable strict mode temporarily
        DB::statement('SET sql_mode = ""');
        
        Schema::table('sesi_assessment', function (Blueprint $table) {
            if (!Schema::hasColumn('sesi_assessment', 'kategori_studi_kasus_id')) {
                $table->unsignedBigInteger('kategori_studi_kasus_id')->nullable()->after('penilaian_id');
            }
        });
        
        // Add foreign key and index using raw SQL
        if (Schema::hasColumn('sesi_assessment', 'kategori_studi_kasus_id')) {
            // Check if foreign key doesn't exist
            $foreignKeyExists = DB::select("
                SELECT COUNT(*) as count 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'sesi_assessment' 
                AND CONSTRAINT_NAME = 'sesi_assessment_kategori_fk'
            ");
            
            if ($foreignKeyExists[0]->count == 0) {
                DB::unprepared("
                    ALTER TABLE sesi_assessment 
                    ADD CONSTRAINT sesi_assessment_kategori_fk 
                    FOREIGN KEY (kategori_studi_kasus_id) 
                    REFERENCES kategori_studi_kasus(id) 
                    ON DELETE SET NULL
                ");
            }
            
            // Add index if it doesn't exist
            $indexExists = DB::select("
                SELECT COUNT(*) as count 
                FROM information_schema.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'sesi_assessment' 
                AND INDEX_NAME = 'sesi_assessment_kategori_studi_kasus_id_index'
            ");
            
            if ($indexExists[0]->count == 0) {
                DB::unprepared("CREATE INDEX sesi_assessment_kategori_studi_kasus_id_index ON sesi_assessment(kategori_studi_kasus_id)");
            }
        }
        
        // Re-enable strict mode
        DB::statement('SET sql_mode = "ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sesi_assessment', function (Blueprint $table) {
            if (Schema::hasColumn('sesi_assessment', 'kategori_studi_kasus_id')) {
                $table->dropForeign(['kategori_studi_kasus_id']);
                $table->dropIndex(['kategori_studi_kasus_id']);
                $table->dropColumn('kategori_studi_kasus_id');
            }
        });
    }
};

