<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penilaian;

class UpdateInTrayModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Updating in-tray model for existing data...');
        
        // Get all in-tray assessments
        $inTrayAssessments = Penilaian::where('jenis', 'in_tray')->get();
        
        if ($inTrayAssessments->isEmpty()) {
            $this->command->info('No in-tray assessments found.');
            return;
        }
        
        $this->command->info('Found ' . $inTrayAssessments->count() . ' in-tray assessments.');
        
        foreach ($inTrayAssessments as $assessment) {
            $this->command->info('Processing: ' . $assessment->nama);
            
            // Check current model_in_tray value
            $currentModel = $assessment->model_in_tray ?? 'urutan';
            $this->command->info('  Current model: ' . $currentModel);
            
            // Ask user what model to set for this assessment
            $choice = $this->command->choice(
                'What model should be used for "' . $assessment->nama . '"?',
                ['urutan', 'prioritas', 'skip'],
                'urutan'
            );
            
            if ($choice === 'skip') {
                $this->command->info('  Skipped.');
                continue;
            }
            
            // Update the model
            $assessment->update(['model_in_tray' => $choice]);
            $this->command->info('  Updated to: ' . $choice);
        }
        
        $this->command->info('Update completed!');
    }
}
