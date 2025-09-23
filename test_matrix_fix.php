<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Test Matrix Controller Fix:\n\n";

// Simulasi request dengan parameter sesi 6
$_GET['sesi'] = 6;

// Simulasi user yang login
$user = \App\Models\User::where('role', 'user')->first();
if (!$user) {
    echo "Tidak ada user untuk test\n";
    exit;
}

echo "User: " . $user->name . " (ID: " . $user->id . ")\n";

// Simulasi peserta
$peserta = $user->peserta;
if (!$peserta) {
    echo "User tidak memiliki data peserta\n";
    exit;
}

echo "Peserta: " . $peserta->nama_lengkap . " (ID: " . $peserta->id . ")\n\n";

// Simulasi logika controller yang sudah diperbaiki
$requestedSesiId = request()->query('sesi');
echo "Requested Sesi ID: " . $requestedSesiId . "\n";

if ($requestedSesiId) {
    // Get the specific session if participant is registered
    $sesi = \App\Models\SesiPenilaian::whereHas('participants', function($query) use ($peserta) {
        $query->where('peserta_id', $peserta->id);
    })->where('id', $requestedSesiId)->where('status', 'active')->first();
} else {
    // Get the active session for this participant
    $sesi = \App\Models\SesiPenilaian::whereHas('participants', function($query) use ($peserta) {
        $query->where('peserta_id', $peserta->id);
    })->where('status', 'active')->first();
}

if (!$sesi) {
    echo "Tidak ada sesi aktif untuk peserta ini\n";
    exit;
}

echo "Sesi ditemukan: " . $sesi->nama . " (ID: " . $sesi->id . ")\n\n";

// Get in-tray assessment for this session
$inTrayAssessment = $sesi->assessments()
    ->whereHas('penilaian', function($query) {
        $query->where('jenis', 'in_tray')
              ->where('model_in_tray', 'prioritas');
    })
    ->with('penilaian')
    ->first();

if (!$inTrayAssessment) {
    echo "TIDAK DITEMUKAN assessment in-tray dengan mode prioritas untuk sesi ini\n";
    
    // Debug: cek semua assessment untuk sesi ini
    echo "\nDebug - Semua assessment untuk sesi " . $sesi->id . ":\n";
    $allAssessments = $sesi->assessments()->with('penilaian')->get();
    foreach ($allAssessments as $assessment) {
        if ($assessment->penilaian) {
            echo "  Assessment ID: " . $assessment->penilaian_id . " - Jenis: " . $assessment->penilaian->jenis . " - Model: " . ($assessment->penilaian->model_in_tray ?? 'urutan') . "\n";
        }
    }
} else {
    echo "Assessment ditemukan:\n";
    echo "  SesiAssessment ID: " . $inTrayAssessment->id . "\n";
    echo "  Assessment ID: " . $inTrayAssessment->penilaian_id . "\n";
    echo "  Assessment Nama: " . $inTrayAssessment->penilaian->nama . "\n";
    echo "  Model: " . ($inTrayAssessment->penilaian->model_in_tray ?? 'urutan') . "\n";
    
    // Cek memo
    $memos = \App\Models\LatihanInTray::where('penilaian_id', $inTrayAssessment->penilaian_id)
        ->where('sesi_penilaian_id', $sesi->id)
        ->where('aktif', true)
        ->count();
    echo "  Jumlah Memo: " . $memos . "\n";
}
