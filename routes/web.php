<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\InTrayMatrixController;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to participant.login
Route::get('/', function () {
    return redirect()->route('participant.login');
});

// Redirect Admin Page to login
Route::get('/backoffice', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Participant Login with PIN
Route::get('/participant/login', [AuthController::class, 'showParticipantLogin'])->name('participant.login');
Route::post('/participant/login', [AuthController::class, 'participantLogin']);

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // Petunjuk Penggunaan Admin
    Route::view('/petunjuk', 'admin.petunjuk')->name('petunjuk');
    
    // Sesi Management
    Route::get('/sesi', [AdminController::class, 'sesiIndex'])->name('sesi.index');
    Route::get('/sesi/create', [AdminController::class, 'sesiCreate'])->name('sesi.create');
    Route::post('/sesi', [AdminController::class, 'sesiStore'])->name('sesi.store');
    Route::get('/sesi/{id}', [AdminController::class, 'sesiShow'])->name('sesi.show');
    Route::get('/sesi/{id}/edit', [AdminController::class, 'sesiEdit'])->name('sesi.edit');
    Route::put('/sesi/{id}', [AdminController::class, 'sesiUpdate'])->name('sesi.update');
    Route::patch('/sesi/{id}/status', [AdminController::class, 'sesiUpdateStatus'])->name('sesi.update-status');
    Route::delete('/sesi/{id}', [AdminController::class, 'sesiDestroy'])->name('sesi.destroy');
    Route::post('/sesi/{id}/duplicate', [AdminController::class, 'sesiDuplicate'])->name('sesi.duplicate');
    
    // Sesi Peserta Management
    Route::get('/sesi/{id}/peserta', [AdminController::class, 'sesiPeserta'])->name('sesi.peserta');
    Route::post('/sesi/{id}/peserta', [AdminController::class, 'sesiPesertaStore'])->name('sesi.peserta.store');
    Route::delete('/sesi/{id}/peserta/{pesertaId}', [AdminController::class, 'sesiPesertaDestroy'])->name('sesi.peserta.destroy');
    
    // Assessment PDF Management
    Route::post('/assessment/{penilaianId}/upload-pdf', [AdminController::class, 'uploadAssessmentPdf'])->name('assessment.upload-pdf');
    Route::delete('/assessment/{penilaianId}/delete-pdf', [AdminController::class, 'deleteAssessmentPdf'])->name('assessment.delete-pdf');
    
    // Peserta Management
    Route::get('/peserta', [AdminController::class, 'pesertaIndex'])->name('peserta.index');
    Route::get('/peserta/create', [AdminController::class, 'pesertaCreate'])->name('peserta.create');
    Route::post('/peserta', [AdminController::class, 'pesertaStore'])->name('peserta.store');
    Route::get('/peserta/{id}', [AdminController::class, 'pesertaShow'])->name('peserta.show');
    Route::get('/peserta/{id}/edit', [AdminController::class, 'pesertaEdit'])->name('peserta.edit');
    Route::put('/peserta/{id}', [AdminController::class, 'pesertaUpdate'])->name('peserta.update');
    Route::delete('/peserta/{id}', [AdminController::class, 'pesertaDestroy'])->name('peserta.destroy');
    
    // Progress Monitoring
    Route::get('/progress', [AdminController::class, 'progressIndex'])->name('progress.index');
    Route::get('/progress/data', [AdminController::class, 'getProgressData'])->name('progress.data');
    Route::get('/progress/export', [AdminController::class, 'exportProgress'])->name('progress.export');
    
    // Progress Answers (must be before /progress/{pesertaId} to avoid route conflict)
    Route::get('/progress/answers', [AdminController::class, 'progressAnswers'])->name('progress.answers');
    Route::get('/progress/export-answers', [AdminController::class, 'exportAnswers'])->name('progress.export-answers');
    Route::get('/progress/answer-detail', [AdminController::class, 'getAnswerDetail'])->name('progress.answer-detail');
    Route::post('/progress/save-penilaian-studi-kasus', [AdminController::class, 'savePenilaianStudiKasus'])->name('progress.save-penilaian-studi-kasus');
    Route::put('/progress/status-by-keys', [AdminController::class, 'updateProgressStatusByKeys'])->name('progress.update-status-by-keys');
    
    // Progress Individual (must be after specific routes)
    Route::get('/progress/{pesertaId}', [AdminController::class, 'progressPeserta'])->name('progress.peserta');
    Route::put('/progress/{kemajuanId}/status', [AdminController::class, 'updateProgressStatus'])->name('progress.update-status');
    
    // Review Jawaban
    Route::get('/review/studi-kasus', [AdminController::class, 'reviewStudiKasus'])->name('review.studi-kasus');
    Route::get('/review/in-tray', [AdminController::class, 'reviewInTray'])->name('review.in-tray');
    Route::get('/review/roleplay', [AdminController::class, 'reviewRoleplay'])->name('review.roleplay');
    Route::get('/review/fgd', [AdminController::class, 'reviewFgd'])->name('review.fgd');
    
    // Export Review
    Route::get('/review/roleplay/export', [AdminController::class, 'exportRoleplay'])->name('review.roleplay.export');
    Route::get('/review/fgd/export', [AdminController::class, 'exportFgd'])->name('review.fgd.export');
    
    // Assessment Inputs Management
    Route::get('/assessment-inputs', [App\Http\Controllers\Admin\AssessmentInputController::class, 'index'])->name('assessment-inputs.index');
    Route::get('/assessment-inputs/export', [App\Http\Controllers\Admin\AssessmentInputController::class, 'export'])->name('assessment-inputs.export');
    
    // In-Tray Matrix
    Route::get('/intray-matrix/{sesiId}/{pesertaId}', [InTrayMatrixController::class, 'show'])->name('intray-matrix.show');
    Route::get('/intray-matrix/{sesiId}/{pesertaId}/data', [InTrayMatrixController::class, 'getMatrixData'])->name('intray-matrix.data');
    
    
    // Manajemen Urutan Assessment


     // Import Peserta CSV
     Route::post('/peserta/import', [AdminController::class, 'importPeserta'])->name('peserta.import');
     Route::get('/peserta/template', [AdminController::class, 'downloadTemplateCsv'])->name('peserta.template');
     
     // Debug Storage (untuk troubleshooting)
     Route::get('/debug/storage', [AdminController::class, 'debugStorage'])->name('debug.storage');
     
     // Test File Upload (untuk troubleshooting)
     Route::post('/test/upload', [AdminController::class, 'testFileUpload'])->name('test.upload');

     

     
});

// Route untuk mengakses file PDF assessment (mendukung path bertingkat) - di luar grup admin agar dapat diakses peserta
Route::get('/admin/assessment/{penilaianId}/pdf/{filename}', function($penilaianId, $filename) {
    $relativePath = Str::startsWith($filename, 'assessments/pdf') ? $filename : ('assessments/pdf/' . $filename);
    $path = storage_path('app/public/' . $relativePath);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . basename($relativePath) . '"'
    ]);
})->where('filename', '.*')->name('assessment.pdf.view');

// Route khusus untuk inline PDF display (mencegah download)
Route::get('/pdf/inline/{penilaianId}/{filename}', function($penilaianId, $filename) {
    $relativePath = Str::startsWith($filename, 'assessments/pdf') ? $filename : ('assessments/pdf/' . $filename);
    $path = storage_path('app/public/' . $relativePath);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . basename($relativePath) . '"',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0',
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'SAMEORIGIN'
    ]);
})->where('filename', '.*')->name('pdf.inline');

// Routes untuk Peserta (dengan auth middleware)
Route::prefix('peserta')->name('peserta.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PesertaController::class, 'dashboard'])->name('dashboard');
    // Petunjuk Penggunaan Peserta
    Route::view('/petunjuk', 'peserta.petunjuk')->name('petunjuk');
    Route::get('/biodata', [PesertaController::class, 'showBiodata'])->name('biodata');
    Route::get('/penilaian/{penilaianId}', [PesertaController::class, 'showPenilaian'])->name('penilaian');
    Route::post('/logout', [PesertaController::class, 'logout'])->name('logout');
    
    // Sesi Routes
    Route::get('/sesi/{id}/detail', [PesertaController::class, 'showSesiDetail'])->name('sesi.detail');
    Route::get('/sesi/{id}/mulai', [PesertaController::class, 'mulaiSesi'])->name('sesi.mulai');
    
    // Assessment Routes
    Route::get('/assessment/{id}/kerja', [PesertaController::class, 'showAssessmentKerja'])->name('assessment.kerja');
    
    // Studi Kasus Routes
    Route::get('/assessment/{id}/studi-kasus', [PesertaController::class, 'showStudiKasus'])->name('assessment.studi-kasus');
    Route::post('/assessment/{id}/studi-kasus', [PesertaController::class, 'storeStudiKasus'])->name('assessment.studi-kasus.store');
    Route::get('/penilaian-studi-kasus/{penilaianId}', [PesertaController::class, 'getPenilaianStudiKasus'])->name('penilaian.studi-kasus');
    
    // Test route untuk debugging
    Route::get('/test-studi-kasus/{id}', [PesertaController::class, 'showStudiKasus'])->name('test.studi-kasus');
    
    // In-Tray Matrix (for participants)
    Route::get('/intray-matrix', [InTrayMatrixController::class, 'show'])->name('intray-matrix.show');
});

// Test route tanpa middleware untuk debugging studi kasus
Route::get('/debug/studi-kasus/{id}', [PesertaController::class, 'testStudiKasus'])->name('debug.studi-kasus');

// Test route untuk debugging showStudiKasus tanpa middleware
Route::get('/debug/show-studi-kasus/{id}', [PesertaController::class, 'showStudiKasus'])->name('debug.show-studi-kasus');


// Route test sederhana untuk debugging view
Route::get('/simple-test/{id}', function($id) {
    try {
        $assessment = App\Models\Penilaian::with(['sesiPenilaian'])->findOrFail($id);
        $peserta = new stdClass();
        $peserta->nama_lengkap = 'Test User';
        $existingJawaban = '';
        
        return view('peserta.assessment-studi-kasus', compact('peserta', 'assessment', 'existingJawaban'));
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'id' => $id
        ]);
    }
})->name('simple.test');

// Routes untuk menyimpan jawaban/catatan
Route::post('/penilaian/studi-kasus/{penilaianId}/save', [PenilaianController::class, 'saveJawabanStudiKasus'])->name('penilaian.studi-kasus.save');
Route::post('/penilaian/in-tray/{penilaianId}/save', [PenilaianController::class, 'saveJawabanInTray'])->name('penilaian.in-tray.save');
Route::post('/penilaian/in-tray/{penilaianId}/update-disposisi', [PenilaianController::class, 'updateDisposisiInTray'])->name('penilaian.in-tray.update-disposisi');
Route::post('/penilaian/roleplay/{penilaianId}/save', [PenilaianController::class, 'saveCatatanRoleplay'])->name('penilaian.roleplay.save');
Route::post('/penilaian/fgd/{penilaianId}/save', [PenilaianController::class, 'saveCatatanFgd'])->name('penilaian.fgd.save');
