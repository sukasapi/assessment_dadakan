<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PenilaianController;

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

// Redirect root to peserta login
Route::get('/', function () {
    return redirect()->route('peserta.login');
});

// Routes untuk Peserta
Route::get('/peserta/login', [PesertaController::class, 'showLogin'])->name('peserta.login');
Route::post('/peserta/login', [PesertaController::class, 'login']);
Route::get('/peserta/dashboard', [PesertaController::class, 'dashboard'])->name('peserta.dashboard');
Route::get('/peserta/biodata', [PesertaController::class, 'showBiodata'])->name('peserta.biodata');
Route::get('/peserta/penilaian/{penilaianId}', [PesertaController::class, 'showPenilaian'])->name('peserta.penilaian');
Route::post('/peserta/logout', [PesertaController::class, 'logout'])->name('peserta.logout');

// Routes untuk menyimpan jawaban/catatan
Route::post('/penilaian/studi-kasus/{penilaianId}/save', [PenilaianController::class, 'saveJawabanStudiKasus'])->name('penilaian.studi-kasus.save');
Route::post('/penilaian/in-tray/{penilaianId}/save', [PenilaianController::class, 'saveJawabanInTray'])->name('penilaian.in-tray.save');
Route::post('/penilaian/roleplay/{penilaianId}/save', [PenilaianController::class, 'saveCatatanRoleplay'])->name('penilaian.roleplay.save');
Route::post('/penilaian/fgd/{penilaianId}/save', [PenilaianController::class, 'saveCatatanFgd'])->name('penilaian.fgd.save');
