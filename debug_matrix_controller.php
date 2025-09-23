<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Debug InTrayMatrixController - Sesi 6:\n\n";

// Simulasi logika controller untuk sesi 6
$sesi = \App\Models\SesiPenilaian::find(6);
if (!$sesi) {
    echo "Sesi 6 tidak ditemukan!\n";
    exit;
}

echo "Sesi: " . $sesi->nama . " (ID: " . $sesi->id . ")\n\n";

// Cek assessments untuk sesi ini
echo "SesiAssessment untuk sesi 6:\n";
$sesiAssessments = $sesi->assessments()->with('penilaian')->get();
foreach ($sesiAssessments as $sesiAssessment) {
    if ($sesiAssessment->penilaian) {
        echo "  SesiAssessment ID: " . $sesiAssessment->id . " - Assessment ID: " . $sesiAssessment->penilaian_id . " - Jenis: " . $sesiAssessment->penilaian->jenis . " - Model: " . ($sesiAssessment->penilaian->model_in_tray ?? 'urutan') . "\n";
    }
}

echo "\nMencari assessment in-tray dengan mode prioritas:\n";
$inTrayAssessment = $sesi->assessments()
    ->whereHas('penilaian', function($query) {
        $query->where('jenis', 'in_tray')
              ->where('model_in_tray', 'prioritas');
    })
    ->with('penilaian')
    ->first();

if ($inTrayAssessment) {
    echo "Ditemukan assessment:\n";
    echo "  SesiAssessment ID: " . $inTrayAssessment->id . "\n";
    echo "  Assessment ID: " . $inTrayAssessment->penilaian_id . "\n";
    echo "  Assessment Nama: " . $inTrayAssessment->penilaian->nama . "\n";
    echo "  Model: " . ($inTrayAssessment->penilaian->model_in_tray ?? 'urutan') . "\n";
} else {
    echo "TIDAK DITEMUKAN assessment in-tray dengan mode prioritas!\n";
    
    // Cek semua assessment in-tray untuk sesi ini
    echo "\nSemua assessment in-tray untuk sesi 6:\n";
    $allInTray = $sesi->assessments()
        ->whereHas('penilaian', function($query) {
            $query->where('jenis', 'in_tray');
        })
        ->with('penilaian')
        ->get();
    
    foreach ($allInTray as $assessment) {
        if ($assessment->penilaian) {
            echo "  Assessment ID: " . $assessment->penilaian_id . " - Model: " . ($assessment->penilaian->model_in_tray ?? 'urutan') . "\n";
        }
    }
}

echo "\nCek langsung dari database:\n";
$directCheck = \App\Models\SesiAssessment::where('sesi_penilaian_id', 6)
    ->whereHas('penilaian', function($query) {
        $query->where('jenis', 'in_tray')
              ->where('model_in_tray', 'prioritas');
    })
    ->with('penilaian')
    ->first();

if ($directCheck) {
    echo "Direct check berhasil - Assessment ID: " . $directCheck->penilaian_id . "\n";
} else {
    echo "Direct check gagal!\n";
}
