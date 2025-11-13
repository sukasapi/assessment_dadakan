<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KemajuanPenilaian;
use App\Models\JawabanStudiKasus;
use App\Models\Penilaian;
use Illuminate\Support\Facades\DB;

class MigrateJawabanStudiKasusFromKemajuan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jawaban:migrate-from-kemajuan 
                            {--dry-run : Menampilkan preview tanpa melakukan update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate jawaban studi kasus dari kemajuan_penilaian.jawaban ke jawaban_studi_kasus';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('🔍 Mencari jawaban studi kasus di kemajuan_penilaian...');
        $this->newLine();

        // Ambil semua kemajuan penilaian yang memiliki jawaban dan penilaian jenis studi_kasus
        $kemajuanWithJawaban = DB::table('kemajuan_penilaian')
            ->join('penilaian', 'kemajuan_penilaian.penilaian_id', '=', 'penilaian.id')
            ->where('penilaian.jenis', 'studi_kasus')
            ->whereNotNull('kemajuan_penilaian.jawaban')
            ->where('kemajuan_penilaian.jawaban', '!=', '')
            ->select('kemajuan_penilaian.*', 'penilaian.jenis')
            ->get();

        if ($kemajuanWithJawaban->isEmpty()) {
            $this->info('✅ Tidak ada data yang perlu dimigrasi.');
            return Command::SUCCESS;
        }

        $total = $kemajuanWithJawaban->count();
        $this->info("📊 Ditemukan {$total} record yang perlu dimigrasi.");
        $this->newLine();

        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODE - Tidak ada perubahan yang akan dilakukan');
            $this->newLine();
        }

        $successCount = 0;
        $skippedCount = 0;
        $failedCount = 0;
        $errors = [];

        // Progress bar
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($kemajuanWithJawaban as $kemajuan) {
            try {
                // Cek apakah sudah ada di jawaban_studi_kasus
                $existingJawaban = JawabanStudiKasus::where('peserta_id', $kemajuan->peserta_id)
                    ->where('penilaian_id', $kemajuan->penilaian_id)
                    ->where('sesi_penilaian_id', $kemajuan->sesi_penilaian_id)
                    ->first();

                if ($existingJawaban) {
                    $skippedCount++;
                    $bar->advance();
                    continue;
                }

                // Migrate jika bukan dry-run
                if (!$dryRun) {
                    JawabanStudiKasus::create([
                        'peserta_id' => $kemajuan->peserta_id,
                        'penilaian_id' => $kemajuan->penilaian_id,
                        'sesi_penilaian_id' => $kemajuan->sesi_penilaian_id,
                        'jawaban' => $kemajuan->jawaban,
                        'status' => $kemajuan->status === 'selesai' ? 'final' : 'draft',
                        'waktu_simpan' => $kemajuan->aktivitas_terakhir ?? now(),
                    ]);
                }

                $successCount++;
                $bar->advance();

            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = [
                    'kemajuan_id' => $kemajuan->id,
                    'peserta_id' => $kemajuan->peserta_id,
                    'penilaian_id' => $kemajuan->penilaian_id,
                    'error' => $e->getMessage()
                ];
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Tampilkan statistik
        $this->info('📈 Statistik Migrasi:');
        $this->table(
            ['Status', 'Jumlah'],
            [
                ['✅ Berhasil dimigrasi', $successCount],
                ['⏭️  Dilewati (sudah ada)', $skippedCount],
                ['❌ Gagal', $failedCount],
                ['📊 Total', $total],
            ]
        );

        // Tampilkan error jika ada
        if (!empty($errors)) {
            $this->newLine();
            $this->warn('⚠️  Detail Error:');
            
            if ($this->option('verbose') || count($errors) <= 10) {
                $this->table(
                    ['Kemajuan ID', 'Peserta ID', 'Penilaian ID', 'Error'],
                    array_map(function($error) {
                        return [
                            $error['kemajuan_id'],
                            $error['peserta_id'],
                            $error['penilaian_id'],
                            $error['error']
                        ];
                    }, $errors)
                );
            } else {
                $this->warn('Gunakan --verbose untuk melihat detail error.');
            }
        }

        $this->newLine();

        if ($dryRun) {
            $this->info('💡 Untuk melakukan migrasi sebenarnya, jalankan command tanpa --dry-run');
        } else {
            if ($successCount > 0) {
                $this->info("✅ Berhasil memigrasi {$successCount} record.");
            }
            if ($failedCount > 0 || $skippedCount > 0) {
                $this->warn("⚠️  Ada {$failedCount} record yang gagal dan {$skippedCount} record yang dilewati.");
            }
        }

        return Command::SUCCESS;
    }
}

