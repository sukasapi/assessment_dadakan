<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\SesiPenilaian;
use App\Models\Penilaian;
use App\Models\KemajuanPenilaian;
use App\Models\PenilaianStudiKasus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\LatihanInTray;
use App\Models\JawabanInTray;
use App\Models\ItemPenilaian;
use App\Models\CatatanRoleplay;
use App\Models\CatatanFgd;

class PesertaController extends Controller
{
    public function showLogin()
    {
        return view('participant.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|min:6|max:6'
        ]);

        $peserta = Peserta::where('pin', $request->pin)
                         ->where('aktif', true)
                         ->first();

        if (!$peserta) {
            return back()->withErrors(['pin' => 'PIN tidak valid atau peserta tidak aktif.']);
        }

        // Set session
        session(['peserta_id' => $peserta->id]);
        session(['peserta_name' => $peserta->nama_lengkap]);

        return redirect()->route('peserta.dashboard');
    }

    public function dashboard()
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('participant.login');
        }

        // Cek apakah ada session peserta_id
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            // Jika tidak ada session peserta_id, coba ambil dari user yang login
            $user = Auth::user();
            $peserta = Peserta::where('user_id', $user->id)->first();
            
            if ($peserta) {
                // Set session peserta
                session(['peserta_id' => $peserta->id]);
                session(['peserta_name' => $peserta->nama_lengkap]);
                $pesertaId = $peserta->id;
            } else {
                return redirect()->route('participant.login');
            }
        }

        $peserta = Peserta::findOrFail($pesertaId);
        
        // Ambil semua sesi dimana peserta teregistrasi
        $sesiList = SesiPenilaian::whereHas('participants', function($query) use ($pesertaId) {
            $query->where('peserta_id', $pesertaId);
        })
        ->with(['assessments.penilaian'])
        ->orderBy('created_at', 'desc')
        ->get();

        // Ambil status kemajuan per penilaian untuk peserta ini dengan sesi_penilaian_id
        $kemajuanList = KemajuanPenilaian::where('peserta_id', $pesertaId)
            ->get(['penilaian_id', 'sesi_penilaian_id', 'status']);
        
        // Buat map dengan key kombinasi penilaian_id dan sesi_penilaian_id
        $progressMap = $kemajuanList->mapWithKeys(function ($item) {
            return [$item->penilaian_id . '_' . $item->sesi_penilaian_id => $item->status];
        });

        return view('peserta.dashboard', compact('peserta', 'sesiList', 'progressMap'));
    }

    public function showPenilaian($penilaianId)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('participant.login');
        }

        // Cek apakah ada session peserta_id
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            // Jika tidak ada session peserta_id, coba ambil dari user yang login
            $user = Auth::user();
            $peserta = Peserta::where('user_id', $user->id)->first();
            
            if ($peserta) {
                // Set session peserta
                session(['peserta_id' => $peserta->id]);
                session(['peserta_name' => $peserta->nama_lengkap]);
                $pesertaId = $peserta->id;
            } else {
                return redirect()->route('participant.login');
            }
        }

        $peserta = Peserta::findOrFail($pesertaId);
        $penilaian = Penilaian::findOrFail($penilaianId);
        // Jika ada param sesi di URL, pastikan mengambil sesi_assessment pada sesi tsb
        $requestedSesiId = (int) request()->query('sesi');
        $sesiAssessmentQuery = \App\Models\SesiAssessment::where('penilaian_id', $penilaian->id);
        if ($requestedSesiId) {
            $sesiAssessmentQuery->where('sesi_penilaian_id', $requestedSesiId);
        } else {
            $sesiAssessmentQuery->where('sesi_penilaian_id', $penilaian->sesi_penilaian_id);
        }
        $sesiAssessment = $sesiAssessmentQuery->first();
        if (!$sesiAssessment) {
            // fallback lama
            $sesiAssessment = \App\Models\SesiAssessment::where('sesi_penilaian_id', $penilaian->sesi_penilaian_id)
            ->where('penilaian_id', $penilaian->id)
            ->first();
        }
        
        // Cek apakah penilaian aktif
        if (!$penilaian->isActive()) {
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Penilaian tidak aktif.']);
        }

        // Validasi urutan assessment
        if (!$this->canAccessAssessment($peserta, $penilaian)) {
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Anda harus menyelesaikan assessment sebelumnya terlebih dahulu.']);
        }

        // Buat atau update progress
        KemajuanPenilaian::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId,
                'sesi_penilaian_id' => $sesiAssessment->sesi_penilaian_id
            ],
            [
                'status' => 'sedang_berlangsung',
                'aktivitas_terakhir' => now()
            ]
        );

        return view('peserta.penilaian', compact('penilaian', 'sesiAssessment'));
    }

    /**
     * Cek apakah peserta dapat mengakses assessment berdasarkan urutan
     */
    private function canAccessAssessment(Peserta $peserta, Penilaian $penilaian): bool
    {
        // Jika assessment pertama, selalu bisa diakses
        if ($penilaian->isFirstAssessment()) {
            return true;
        }

        // Cek apakah assessment sebelumnya sudah selesai
        $previousAssessment = $penilaian->getPreviousAssessment();
        if (!$previousAssessment) {
            return true; // Tidak ada assessment sebelumnya
        }

        $previousProgress = KemajuanPenilaian::where('peserta_id', $peserta->id)
                                           ->where('penilaian_id', $previousAssessment->id)
                                           ->first();

        // Bisa diakses jika assessment sebelumnya sudah selesai
        return $previousProgress && $previousProgress->status === 'selesai';
    }

    public function showBiodata()
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('participant.login');
        }

        // Cek apakah ada session peserta_id
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            // Jika tidak ada session peserta_id, coba ambil dari user yang login
            $user = Auth::user();
            $peserta = Peserta::where('user_id', $user->id)->first();
            
            if ($peserta) {
                // Set session peserta
                session(['peserta_id' => $peserta->id]);
                session(['peserta_name' => $peserta->nama_lengkap]);
                $pesertaId = $peserta->id;
            } else {
                return redirect()->route('participant.login');
            }
        }

        $peserta = Peserta::findOrFail($pesertaId);
        return view('peserta.biodata', compact('peserta'));
    }

    public function logout()
    {
        // Logout user
        Auth::logout();
        
        // Clear session
        session()->forget(['peserta_id', 'peserta_name']);
        
        return redirect()->route('participant.login');
    }

    /**
     * Tampilkan detail sesi
     */
    public function showSesiDetail($id)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('participant.login');
        }

        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            $user = Auth::user();
            $peserta = Peserta::where('user_id', $user->id)->first();
            
            if ($peserta) {
                session(['peserta_id' => $peserta->id]);
                session(['peserta_name' => $peserta->nama_lengkap]);
                $pesertaId = $peserta->id;
            } else {
                return redirect()->route('participant.login');
            }
        }

        $peserta = Peserta::findOrFail($pesertaId);
        $sesi = SesiPenilaian::with(['assessments.penilaian'])->findOrFail($id);
        
        // Cek apakah peserta teregistrasi di sesi ini
        $isRegistered = \App\Models\AssessmentParticipant::where('sesi_penilaian_id', $sesi->id)
            ->where('peserta_id', (int) $pesertaId)
            ->exists();
        if (!$isRegistered) {
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Anda tidak teregistrasi di sesi ini.']);
        }

        return view('peserta.sesi-detail', compact('peserta', 'sesi'));
    }

    /**
     * Mulai sesi assessment
     */
    public function mulaiSesi($id)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('participant.login');
        }

        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            $user = Auth::user();
            $peserta = Peserta::where('user_id', $user->id)->first();
            
            if ($peserta) {
                session(['peserta_id' => $peserta->id]);
                session(['peserta_name' => $peserta->nama_lengkap]);
                $pesertaId = $peserta->id;
            } else {
                return redirect()->route('participant.login');
            }
        }

        $peserta = Peserta::findOrFail($pesertaId);
        $sesi = SesiPenilaian::with(['assessments.penilaian'])->findOrFail($id);
        
        // Cek apakah peserta teregistrasi di sesi ini
        $isRegistered = \App\Models\AssessmentParticipant::where('sesi_penilaian_id', $sesi->id)
            ->where('peserta_id', (int) $pesertaId)
            ->exists();
            echo $isRegistered;
            exit;
        if (!$isRegistered) {
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Anda tidak teregistrasi di sesi ini.']);
        }

        // Cek apakah sesi aktif
        if ($sesi->status !== 'active') {
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Sesi belum aktif.']);
        }

        // Redirect ke assessment pertama
        $firstAssessment = $sesi->assessments()->orderBy('urutan')->first();
        if ($firstAssessment) {
            // Pastikan mengirim penilaian_id, bukan sesi_assessment.id
            return redirect()->route('peserta.penilaian', $firstAssessment->penilaian_id);
        }

        return redirect()->route('peserta.dashboard')
                       ->withErrors(['error' => 'Tidak ada assessment dalam sesi ini.']);
    }

    /**
     * Tampilkan halaman pengerjaan assessment
     */
    public function showAssessmentKerja($id)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('participant.login');
        }

        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            $user = Auth::user();
            $peserta = Peserta::where('user_id', $user->id)->first();
            
            if ($peserta) {
                session(['peserta_id' => $peserta->id]);
                session(['peserta_name' => $peserta->nama_lengkap]);
                $pesertaId = $peserta->id;
            } else {
                return redirect()->route('participant.login');
            }
        }

        $peserta = Peserta::findOrFail($pesertaId);
        
        // Pertimbangkan param sesi terlebih dahulu untuk menentukan assessment yang tepat
        $requestedSesiId = (int) request()->query('sesi');
        $participantSesiIds = \App\Models\AssessmentParticipant::where('peserta_id', (int) $pesertaId)
            ->pluck('sesi_penilaian_id')
            ->toArray();
        
        // Logika yang disederhanakan untuk mengambil sesiAssessment
        $assessment = Penilaian::with(['sesiPenilaian'])->find($id);
        if (!$assessment) {
            $sa = \App\Models\SesiAssessment::find($id);
            if ($sa) {
                $assessment = Penilaian::with(['sesiPenilaian'])->findOrFail($sa->penilaian_id);
            } else {
                abort(404);
            }
        }
        
        // Tentukan effectiveSesiId
        $effectiveSesiId = $requestedSesiId ?: $assessment->sesi_penilaian_id;
        
        // Pastikan peserta teregistrasi di sesi ini
        if (!in_array($effectiveSesiId, $participantSesiIds, true)) {
            $effectiveSesiId = \App\Models\SesiAssessment::where('penilaian_id', $assessment->id)
                ->whereIn('sesi_penilaian_id', $participantSesiIds)
                ->value('sesi_penilaian_id') ?: $effectiveSesiId;
        }
        
        // Ambil sesiAssessment dengan logika yang lebih sederhana
        $sesiAssessment = \App\Models\SesiAssessment::where('penilaian_id', $assessment->id)
            ->where('sesi_penilaian_id', $effectiveSesiId)
            ->first();
            
        // Debug log untuk troubleshooting
        Log::info('SesiAssessment Debug', [
            'assessment_id' => $assessment->id,
            'effective_sesi_id' => $effectiveSesiId,
            'requested_sesi_id' => $requestedSesiId,
            'sesi_assessment_id' => $sesiAssessment ? $sesiAssessment->id : 'null',
            'instruksi_khusus' => $sesiAssessment ? $sesiAssessment->instruksi_khusus : 'null'
        ]);
        
        // Debug: Log data assessment
        Log::info('Assessment data:', [
            'id' => $assessment->id,
            'nama' => $assessment->nama,
            'jenis' => $assessment->jenis,
            'sesi_id' => $effectiveSesiId
        ]);
        
        // Cek apakah peserta teregistrasi di sesi ini (dukung skema lama yang menyertakan penilaian_id)
        $query = \App\Models\AssessmentParticipant::where('sesi_penilaian_id', $effectiveSesiId)
            ->where('peserta_id', (int) $pesertaId);
        if (Schema::hasColumn('assessment_participant', 'penilaian_id')) {
            $effectiveSesiIdLocal = $effectiveSesiId; // hindari scope closure warning
            $query = $query->orWhere(function($q) use ($assessment, $pesertaId, $effectiveSesiIdLocal) {
                $q->where('sesi_penilaian_id', $effectiveSesiIdLocal)
                  ->where('peserta_id', (int) $pesertaId)
                  ->where('penilaian_id', $assessment->id);
            });
        }
        $isRegistered = $query->exists();
        if (!$isRegistered) {
            Log::warning('Peserta tidak teregistrasi di sesi', [
                'peserta_id' => $pesertaId,
                'sesi_id' => $effectiveSesiId
            ]);
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Anda tidak teregistrasi di sesi ini.']);
        }

        // Cek apakah sesi aktif
        if ($assessment->sesiPenilaian->status !== 'active') {
            Log::warning('Sesi tidak aktif', [
                'sesi_id' => $assessment->sesi_penilaian_id,
                'status' => $assessment->sesiPenilaian->status
            ]);
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Sesi belum aktif.']);
        }

        // Buat atau update progress
        KemajuanPenilaian::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $id,
                'sesi_penilaian_id' => $effectiveSesiId
            ],
            [
                'status' => 'sedang_berlangsung',
                'aktivitas_terakhir' => now()
            ]
        );

        // Data khusus In-Tray: daftar memo dan jawaban sebelumnya (untuk urutan/disposisi)
        $memos = collect();
        $inTrayAnswers = collect();
        $items = collect();
        $existingRoleplay = '';
        $existingFgd = '';
        if ($assessment->jenis === 'in_tray') {
            // Ambil memo berdasarkan sesi penilaian
            $memos = LatihanInTray::where('sesi_penilaian_id', $effectiveSesiId)
                ->where('penilaian_id', $assessment->id)
                ->where('aktif', true)
                ->orderBy('urutan')
                ->get();
            
            // Jika tidak ada memo untuk sesi ini, coba fallback ke memo yang tidak memiliki sesi_penilaian_id
            // (untuk data lama yang belum diupdate)
            if ($memos->isEmpty()) {
                $memos = LatihanInTray::where('penilaian_id', $assessment->id)
                    ->whereNull('sesi_penilaian_id') // Hanya ambil memo yang tidak memiliki sesi_penilaian_id
                    ->where('aktif', true)
                    ->orderBy('urutan')
                    ->get();
            }

            $inTrayAnswers = JawabanInTray::where('peserta_id', $pesertaId)
                ->where('penilaian_id', $id)
                ->where('sesi_penilaian_id', $effectiveSesiId)
                ->orderBy('urutan_prioritas')
                ->get()
                ->keyBy('latihan_in_tray_id');

            // Jika ada jawaban sebelumnya, urutkan memos berdasarkan prioritas tersimpan
            if ($inTrayAnswers->isNotEmpty()) {
                $memos = $memos->sortBy(function ($memo) use ($inTrayAnswers) {
                    return $inTrayAnswers[$memo->id]->urutan_prioritas ?? ($memo->urutan ?? 9999);
                })->values();
            }
        } else {
            // Ambil daftar item pertanyaan sesuai penilaian & jenis aktif (roleplay/fgd)
            $items = ItemPenilaian::where('penilaian_id', $assessment->id)
                ->where('jenis', $assessment->jenis)
                ->where('aktif', true)
                ->orderBy('urutan')
                ->get();
            // Ambil catatan existing untuk pre-fill editor
            if ($assessment->jenis === 'roleplay') {
                $existingRoleplay = CatatanRoleplay::where('peserta_id', $pesertaId)
                    ->where('penilaian_id', $assessment->id)
                    ->where('sesi_penilaian_id', $effectiveSesiId)
                    ->orderByDesc('waktu_simpan')
                    ->value('catatan') ?: '';
            } elseif ($assessment->jenis === 'fgd') {
                $existingFgd = CatatanFgd::where('peserta_id', $pesertaId)
                    ->where('penilaian_id', $assessment->id)
                    ->where('sesi_penilaian_id', $effectiveSesiId)
                    ->orderByDesc('waktu_simpan')
                    ->value('catatan') ?: '';
            }
        }

        // Tentukan model in-tray yang digunakan dari sesi_assessment
        $intrayModel = $sesiAssessment->model_in_tray ?? 'urutan';
        
        return view('peserta.assessment-kerja', compact('peserta', 'assessment', 'sesiAssessment', 'memos', 'inTrayAnswers', 'items', 'existingRoleplay', 'existingFgd', 'effectiveSesiId', 'intrayModel'));
    }

    /**
     * Tampilkan halaman pengerjaan assessment studi kasus
     */
    public function showStudiKasus($id)
    {
        // Debug: Log informasi request
        Log::info('showStudiKasus called', [
            'id' => $id,
            'auth_check' => Auth::check(),
            'user_id' => Auth::id(),
            'session_peserta_id' => session('peserta_id'),
            'session_peserta_name' => session('peserta_name')
        ]);

        // Cek apakah user sudah login
        if (!Auth::check()) {
            Log::warning('User tidak login, redirect ke participant.login');
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Anda harus login terlebih dahulu.']);
        }

        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            Log::info('Tidak ada session peserta_id, coba ambil dari user');
            $user = Auth::user();
            $peserta = Peserta::where('user_id', $user->id)->first();
            
            if ($peserta) {
                Log::info('Peserta ditemukan dari user_id', ['peserta_id' => $peserta->id]);
                session(['peserta_id' => $peserta->id]);
                session(['peserta_name' => $peserta->nama_lengkap]);
                $pesertaId = $peserta->id;
            } else {
                Log::warning('Peserta tidak ditemukan dari user_id', ['user_id' => $user->id]);
                return redirect()->route('participant.login');
            }
        }

        try {
            $peserta = Peserta::findOrFail($pesertaId);
            $assessment = Penilaian::with(['sesiPenilaian'])->findOrFail($id);
            
            // Gunakan parameter sesi (jika ada) untuk memilih sesi assessment yang tepat
            $requestedSesiId = (int) request()->query('sesi');
            $sesiAssessmentQuery = \App\Models\SesiAssessment::where('penilaian_id', $assessment->id);
            if ($requestedSesiId) {
                $sesiAssessmentQuery->where('sesi_penilaian_id', $requestedSesiId);
            } else {
                $sesiAssessmentQuery->where('sesi_penilaian_id', $assessment->sesi_penilaian_id);
            }
            $sesiAssessment = $sesiAssessmentQuery->first();
            if (!$sesiAssessment) {
                // fallback lama
                $sesiAssessment = \App\Models\SesiAssessment::where('sesi_penilaian_id', $assessment->sesi_penilaian_id)
                    ->where('penilaian_id', $assessment->id)
                    ->first();
            }
            
            Log::info('Assessment data retrieved', [
                'assessment_id' => $assessment->id,
                'assessment_jenis' => $assessment->jenis,
                'sesi_id' => $requestedSesiId ?: $assessment->sesi_penilaian_id,
                'sesi_status' => $assessment->sesiPenilaian->status ?? 'null',
                'sesi_assessment' => $sesiAssessment ? $sesiAssessment->id : 'null'
            ]);
            
            // Cek apakah assessment adalah studi kasus
            if ($assessment->jenis !== 'studi_kasus') {
                Log::warning('Assessment bukan studi kasus', ['jenis' => $assessment->jenis]);
                return redirect()->route('peserta.dashboard')
                               ->withErrors(['error' => 'Assessment ini bukan studi kasus.']);
            }
            
            // Cek apakah peserta teregistrasi di sesi ini (cek langsung ke tabel assessment_participant)
            $cekSesiId = $requestedSesiId ?: $assessment->sesi_penilaian_id;
            $isRegistered = \App\Models\AssessmentParticipant::where('sesi_penilaian_id', $cekSesiId)
                ->where('peserta_id', (int) $pesertaId)
                ->exists();
            Log::info('Check peserta registration', [
                'peserta_id' => $pesertaId,
                'sesi_id' => $cekSesiId,
                'is_registered' => $isRegistered
            ]);
            
            if (!$isRegistered) {
                Log::warning('Peserta tidak teregistrasi di sesi');
                return redirect()->route('peserta.dashboard')
                               ->withErrors(['error' => 'Anda tidak teregistrasi di sesi ini.']);
            }

            // Cek apakah sesi aktif (sementara izinkan pending untuk testing)
            if (!in_array($assessment->sesiPenilaian->status, ['active', 'pending'])) {
                Log::warning('Sesi tidak aktif', ['status' => $assessment->sesiPenilaian->status]);
                return redirect()->route('peserta.dashboard')
                               ->withErrors(['error' => 'Sesi belum aktif.']);
            }

            // Ambil jawaban yang sudah ada dari tabel jawaban_studi_kasus (jika ada)
            $jawabanStudiKasus = \App\Models\JawabanStudiKasus::where('peserta_id', $pesertaId)
                ->where('penilaian_id', $assessment->id)
                ->where('sesi_penilaian_id', $cekSesiId)
                ->first();
            
            $existingJawaban = $jawabanStudiKasus ? $jawabanStudiKasus->jawaban : null;
            $jawabanStatus = $jawabanStudiKasus ? $jawabanStudiKasus->status : null; // 'draft' atau 'final'

            // Cek status kemajuan penilaian
            $kemajuanPenilaian = KemajuanPenilaian::where('peserta_id', $pesertaId)
                ->where('penilaian_id', $assessment->id)
                ->where('sesi_penilaian_id', $cekSesiId)
                ->first();
            
            $statusKemajuan = $kemajuanPenilaian ? $kemajuanPenilaian->status : null; // 'sedang_berlangsung' atau 'selesai'

            // Buat atau update progress (hanya jika belum selesai)
            if (!$kemajuanPenilaian || $kemajuanPenilaian->status !== 'selesai') {
                KemajuanPenilaian::updateOrCreate(
                    [
                        'peserta_id' => $pesertaId,
                        'penilaian_id' => $assessment->id,
                        'sesi_penilaian_id' => $cekSesiId
                    ],
                    [
                        'status' => 'sedang_berlangsung',
                        'aktivitas_terakhir' => now()
                    ]
                );
            }

            // Ambil daftar item pertanyaan studi kasus sesuai penilaian & aktif
            $items = ItemPenilaian::where('penilaian_id', $assessment->id)
                ->where('jenis', 'studi_kasus')
                ->where('aktif', true)
                ->orderBy('urutan')
                ->get();

            // Cek apakah jawaban sudah dinilai oleh admin dengan status final
            $penilaian = PenilaianStudiKasus::where('peserta_id', $pesertaId)
                ->where('penilaian_id', $id)
                ->where('sesi_penilaian_id', $cekSesiId)
                ->where('status', 'final')
                ->first();

            Log::info('Successfully returning view', [
                'view' => 'peserta.assessment-studi-kasus',
                'peserta_id' => $pesertaId,
                'assessment_id' => $id,
                'has_penilaian_final' => $penilaian ? true : false
            ]);

            return view('peserta.assessment-studi-kasus', compact('peserta', 'assessment', 'existingJawaban', 'sesiAssessment', 'items', 'penilaian', 'jawabanStatus', 'statusKemajuan'));
            
        } catch (\Exception $e) {
            Log::error('Error in showStudiKasus: ' . $e->getMessage(), [
                'id' => $id,
                'peserta_id' => $pesertaId ?? 'null',
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Method test untuk debugging studi kasus
     */
    public function testStudiKasus($id)
    {
        return response()->json([
            'message' => 'Test route berhasil diakses',
            'id' => $id,
            'auth_check' => Auth::check(),
            'user_id' => Auth::id(),
            'session_peserta_id' => session('peserta_id'),
            'session_peserta_name' => session('peserta_name'),
            'all_sessions' => session()->all()
        ]);
    }

    /**
     * Simpan jawaban assessment studi kasus
     */
    public function storeStudiKasus(Request $request, $id)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('participant.login');
        }

        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            $user = Auth::user();
            $peserta = Peserta::where('user_id', $user->id)->first();
            
            if ($peserta) {
                session(['peserta_id' => $peserta->id]);
                session(['peserta_name' => $peserta->nama_lengkap]);
                $pesertaId = $peserta->id;
            } else {
                return redirect()->route('participant.login');
            }
        }

        $request->validate([
            'jawaban' => 'required|string|min:10',
            'assessment_action' => 'required|in:draft,final'
        ]);

        $assessment = Penilaian::findOrFail($id);
        
        // Cek apakah assessment adalah studi kasus
        if ($assessment->jenis !== 'studi_kasus') {
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Assessment ini bukan studi kasus.']);
        }

        // Cek apakah peserta teregistrasi di sesi ini
        // Ambil sesi dari query atau body (POST) agar tetap terbaca saat form tidak menyertakan query string
        $requestedSesiId = (int) ($request->query('sesi') ?: $request->input('sesi'));
        $cekSesiId = $requestedSesiId ?: $assessment->sesi_penilaian_id;
        $isRegistered = \App\Models\AssessmentParticipant::where('sesi_penilaian_id', $cekSesiId)
            ->where('peserta_id', (int) $pesertaId)
            ->exists();
        if (!$isRegistered) {
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Anda tidak teregistrasi di sesi ini.']);
        }

        // Update atau buat progress
        // Gunakan nilai status yang sesuai dengan enum kolom di database
        // 'draft' dipetakan ke 'sedang_berlangsung'
        $status = $request->assessment_action === 'final' ? 'selesai' : 'sedang_berlangsung';
        
        // Simpan jawaban ke tabel jawaban_studi_kasus
        $jawabanStatus = $request->assessment_action === 'final' ? 'final' : 'draft';
        \App\Models\JawabanStudiKasus::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $id,
                'sesi_penilaian_id' => $cekSesiId
            ],
            [
                'jawaban' => $request->jawaban,
                'status' => $jawabanStatus,
                'waktu_simpan' => now(),
                'sesi_penilaian_id' => $cekSesiId
            ]
        );
        
        KemajuanPenilaian::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $id,
                'sesi_penilaian_id' => $cekSesiId
            ],
            [
                'status' => $status,
                'waktu_mulai' => now(),
                'waktu_selesai' => $status === 'selesai' ? now() : null,
                'aktivitas_terakhir' => now()
            ]
        );

        $message = $request->assessment_action === 'final'
            ? 'Jawaban berhasil disimpan sebagai final!'
            : 'Jawaban berhasil disimpan sementara.';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $status
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Get penilaian studi kasus untuk peserta (read-only)
     */
    public function getPenilaianStudiKasus(Request $request, $penilaianId)
    {
        try {
            $pesertaId = session('peserta_id');
            if (!$pesertaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $sesiId = $request->get('sesi_id');
            if (!$sesiId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi ID diperlukan'
                ], 400);
            }

            // Query penilaian dengan status final
            $penilaian = PenilaianStudiKasus::where('penilaian_id', $penilaianId)
                ->where('peserta_id', $pesertaId)
                ->where('sesi_penilaian_id', $sesiId)
                ->where('status', 'final')
                ->first();

            if (!$penilaian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penilaian tidak ditemukan atau belum final'
                ], 404);
            }

            // Pastikan peserta_id match (security check)
            if ($penilaian->peserta_id != $pesertaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'pertanyaan_1' => $penilaian->pertanyaan_1,
                    'pertanyaan_2' => $penilaian->pertanyaan_2,
                    'pertanyaan_3' => $penilaian->pertanyaan_3,
                    'catatan' => $penilaian->catatan,
                    'status' => $penilaian->status,
                    'created_at' => $penilaian->created_at,
                    'updated_at' => $penilaian->updated_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting penilaian studi kasus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
