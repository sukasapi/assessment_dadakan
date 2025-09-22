<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penilaian;

class UpdateInTrayToPrioritasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Updating all in-tray assessments to use "prioritas" model...');
        
        // Get all in-tray assessments
        $inTrayAssessments = Penilaian::where('jenis', 'in_tray')->get();
        
        if ($inTrayAssessments->isEmpty()) {
            $this->command->info('No in-tray assessments found.');
            return;
        }
        
        $this->command->info('Found ' . $inTrayAssessments->count() . ' in-tray assessments.');
        
        $updatedCount = 0;
        
        foreach ($inTrayAssessments as $assessment) {
            $this->command->info('Updating: ' . $assessment->nama);
            
            // Check current model_in_tray value
            $currentModel = $assessment->model_in_tray ?? 'urutan';
            $this->command->info('  Current model: ' . $currentModel);
            
            // Update to prioritas model
            $assessment->update(['model_in_tray' => 'prioritas']);
            $this->command->info('  Updated to: prioritas');
            $updatedCount++;
        }
        
        $this->command->info('Update completed! Updated ' . $updatedCount . ' assessments.');
    }
}
