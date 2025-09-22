<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penilaian;

class UpdateInTrayModelFlexibleSeeder extends Seeder
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
        
        $this->command->info('Found ' . $inTrayAssessments->count() . ' in-tray assessments:');
        
        // Display all assessments
        foreach ($inTrayAssessments as $index => $assessment) {
            $currentModel = $assessment->model_in_tray ?? 'urutan';
            $this->command->info(($index + 1) . '. ' . $assessment->nama . ' (ID: ' . $assessment->id . ') - Current: ' . $currentModel);
        }
        
        // Ask for update strategy
        $strategy = $this->command->choice(
            'How would you like to update the models?',
            [
                'all_to_prioritas' => 'Update all to "prioritas" model',
                'all_to_urutan' => 'Update all to "urutan" model',
                'selective' => 'Select specific assessments to update',
                'skip' => 'Skip update'
            ],
            'all_to_prioritas'
        );
        
        if ($strategy === 'skip') {
            $this->command->info('Update skipped.');
            return;
        }
        
        $updatedCount = 0;
        
        if ($strategy === 'all_to_prioritas') {
            foreach ($inTrayAssessments as $assessment) {
                $assessment->update(['model_in_tray' => 'prioritas']);
                $this->command->info('Updated: ' . $assessment->nama . ' -> prioritas');
                $updatedCount++;
            }
        } elseif ($strategy === 'all_to_urutan') {
            foreach ($inTrayAssessments as $assessment) {
                $assessment->update(['model_in_tray' => 'urutan']);
                $this->command->info('Updated: ' . $assessment->nama . ' -> urutan');
                $updatedCount++;
            }
        } elseif ($strategy === 'selective') {
            foreach ($inTrayAssessments as $assessment) {
                $choice = $this->command->choice(
                    'What model for "' . $assessment->nama . '"?',
                    ['urutan', 'prioritas', 'skip'],
                    'urutan'
                );
                
                if ($choice !== 'skip') {
                    $assessment->update(['model_in_tray' => $choice]);
                    $this->command->info('Updated: ' . $assessment->nama . ' -> ' . $choice);
                    $updatedCount++;
                } else {
                    $this->command->info('Skipped: ' . $assessment->nama);
                }
            }
        }
        
        $this->command->info('Update completed! Updated ' . $updatedCount . ' assessments.');
        
        // Display final results
        $this->command->info('Final results:');
        $finalAssessments = Penilaian::where('jenis', 'in_tray')->get();
        foreach ($finalAssessments as $assessment) {
            $model = $assessment->model_in_tray ?? 'urutan';
            $this->command->info('- ' . $assessment->nama . ': ' . $model);
        }
    }
}
