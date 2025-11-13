<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JawabanStudiKasus;
use App\Models\Penilaian;
use Illuminate\Support\Facades\DB;

class UpdateJawabanStudiKasusSesiId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jawaban:update-sesi-id 
                            {--dry-run : Menampilkan preview tanpa melakukan update}
                            {--force : Memaksa update meskipun ada warning}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update sesi_penilaian_id untuk jawaban studi kasus yang sudah ada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('🔍 Mencari jawaban studi kasus yang perlu diupdate...');
        $this->newLine();

        // Ambil semua jawaban studi kasus yang sesi_penilaian_id-nya NULL
        $jawabanToUpdate = JawabanStudiKasus::whereNull('sesi_penilaian_id')
            ->with('penilaian')
            ->get();

        if ($jawabanToUpdate->isEmpty()) {
            $this->info('✅ Tidak ada data yang perlu diupdate. Semua jawaban sudah memiliki sesi_penilaian_id.');
            return Command::SUCCESS;
        }

        $total = $jawabanToUpdate->count();
        $this->info("📊 Ditemukan {$total} record yang perlu diupdate.");
        $this->newLine();

        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODE - Tidak ada perubahan yang akan dilakukan');
            $this->newLine();
        }

        $successCount = 0;
        $failedCount = 0;
        $skippedCount = 0;
        $errors = [];

        // Progress bar
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($jawabanToUpdate as $jawaban) {
            try {
                // Validasi penilaian ada
                if (!$jawaban->penilaian) {
                    $skippedCount++;
                    $errors[] = [
                        'id' => $jawaban->id,
                        'peserta_id' => $jawaban->peserta_id,
                        'penilaian_id' => $jawaban->penilaian_id,
                        'error' => 'Penilaian tidak ditemukan'
                    ];
                    $bar->advance();
                    continue;
                }

                // Ambil sesi_penilaian_id dari penilaian
                $sesiPenilaianId = $jawaban->penilaian->sesi_penilaian_id;

                // Validasi sesi_penilaian_id tidak NULL
                if (!$sesiPenilaianId) {
                    $skippedCount++;
                    $errors[] = [
                        'id' => $jawaban->id,
                        'peserta_id' => $jawaban->peserta_id,
                        'penilaian_id' => $jawaban->penilaian_id,
                        'error' => 'Penilaian tidak memiliki sesi_penilaian_id'
                    ];
                    $bar->advance();
                    continue;
                }

                // Update jika bukan dry-run
                if (!$dryRun) {
                    $jawaban->sesi_penilaian_id = $sesiPenilaianId;
                    $jawaban->save();
                }

                $successCount++;
                $bar->advance();

            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = [
                    'id' => $jawaban->id,
                    'peserta_id' => $jawaban->peserta_id,
                    'penilaian_id' => $jawaban->penilaian_id,
                    'error' => $e->getMessage()
                ];
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Tampilkan statistik
        $this->info('📈 Statistik Update:');
        $this->table(
            ['Status', 'Jumlah'],
            [
                ['✅ Berhasil diupdate', $successCount],
                ['⏭️  Dilewati (tidak valid)', $skippedCount],
                ['❌ Gagal', $failedCount],
                ['📊 Total', $total],
            ]
        );

        // Tampilkan error jika ada
        if (!empty($errors)) {
            $this->newLine();
            $this->warn('⚠️  Detail Error/Skip:');
            
            if ($this->option('verbose') || count($errors) <= 10) {
                $this->table(
                    ['ID', 'Peserta ID', 'Penilaian ID', 'Error'],
                    array_map(function($error) {
                        return [
                            $error['id'],
                            $error['peserta_id'],
                            $error['penilaian_id'],
                            $error['error']
                        ];
                    }, $errors)
                );
            } else {
                $this->warn('Gunakan --verbose untuk melihat detail error.');
                $this->info('Contoh error:');
                $this->line('  - ' . $errors[0]['error']);
            }
        }

        $this->newLine();

        if ($dryRun) {
            $this->info('💡 Untuk melakukan update sebenarnya, jalankan command tanpa --dry-run');
        } else {
            if ($successCount > 0) {
                $this->info("✅ Berhasil mengupdate {$successCount} record.");
            }
            if ($failedCount > 0 || $skippedCount > 0) {
                $this->warn("⚠️  Ada {$failedCount} record yang gagal dan {$skippedCount} record yang dilewati.");
            }
        }

        return Command::SUCCESS;
    }
}

