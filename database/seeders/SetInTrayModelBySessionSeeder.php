<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penilaian;
use App\Models\SesiAssessment;

class SetInTrayModelBySessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Setting in-tray model based on session requirements...');
        
        // Assessment ID 2 digunakan di multiple sessions
        // Sesi 5 (Competency Advancement Batch III) -> urutan (drag-drop)
        // Sesi 6 (sesi tes intray prioritas) -> prioritas (matriks)
        
        // Untuk sesi 5, kita perlu membuat assessment terpisah dengan model urutan
        // Untuk sesi 6, kita bisa menggunakan assessment yang sudah ada dengan model prioritas
        
        $this->command->info('Current assessment 2 model: ' . (Penilaian::find(2)->model_in_tray ?? 'urutan'));
        
        // Buat assessment baru untuk sesi 5 dengan model urutan
        $assessmentForSesi5 = Penilaian::create([
            'sesi_penilaian_id' => 5,
            'nama' => 'In-Tray Exercise (Drag-Drop Model)',
            'jenis' => 'in_tray',
            'petunjuk' => 'Drag and drop memo untuk mengatur prioritas',
            'konten' => '',
            'durasi_menit' => 60,
            'urutan' => 1,
            'aktif' => true,
            'model_in_tray' => 'urutan'
        ]);
        
        $this->command->info('Created new assessment for session 5: ' . $assessmentForSesi5->id);
        
        // Buat SesiAssessment untuk assessment baru di sesi 5
        SesiAssessment::create([
            'penilaian_id' => $assessmentForSesi5->id,
            'sesi_penilaian_id' => 5,
            'aktif' => true,
            'urutan' => 1
        ]);
        
        $this->command->info('Created SesiAssessment for session 5');
        
        // Pastikan assessment 2 menggunakan model prioritas untuk sesi 6
        $assessment2 = Penilaian::find(2);
        if ($assessment2) {
            $assessment2->update(['model_in_tray' => 'prioritas']);
            $this->command->info('Updated assessment 2 to use prioritas model');
        }
        
        // Update assessment 5 dan 6 ke model urutan (jika diperlukan)
        $assessment5 = Penilaian::find(5);
        if ($assessment5) {
            $assessment5->update(['model_in_tray' => 'urutan']);
            $this->command->info('Updated assessment 5 to use urutan model');
        }
        
        $assessment6 = Penilaian::find(6);
        if ($assessment6) {
            $assessment6->update(['model_in_tray' => 'urutan']);
            $this->command->info('Updated assessment 6 to use urutan model');
        }
        
        $this->command->info('Setup completed!');
        
        // Display final results
        $this->command->info('Final assessment configuration:');
        $assessments = Penilaian::where('jenis', 'in_tray')->get(['id', 'nama', 'sesi_penilaian_id', 'model_in_tray']);
        foreach ($assessments as $assessment) {
            echo "ID: " . $assessment->id . " - Sesi: " . $assessment->sesi_penilaian_id . " - Nama: " . $assessment->nama . " - Model: " . ($assessment->model_in_tray ?? 'urutan') . "\n";
        }
    }
}
