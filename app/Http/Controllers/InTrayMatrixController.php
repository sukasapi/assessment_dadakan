<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiPenilaian;
use App\Models\Peserta;
use App\Models\JawabanInTray;
use App\Models\LatihanInTray;
use App\Models\PrioritasMemo;
use Illuminate\Support\Facades\Auth;

class InTrayMatrixController extends Controller
{
    /**
     * Display the in-tray priority matrix for a specific participant
     */
    public function show(Request $request, $sesiId = null, $pesertaId = null)
    {
        $user = Auth::user();
        
        // Determine if user is admin or participant
        $isAdmin = $user && $user->role === 'admin';
        
        if ($isAdmin) {
            // Admin can view any participant's matrix
            if (!$sesiId || !$pesertaId) {
                return redirect()->route('admin.progress.index')->with('error', 'Parameter sesi dan peserta diperlukan');
            }
            
            $sesi = SesiPenilaian::findOrFail($sesiId);
            $peserta = Peserta::findOrFail($pesertaId);
            
            // Verify participant is in the session
            if (!$sesi->participants()->where('peserta_id', $pesertaId)->exists()) {
                return redirect()->route('admin.progress.index')->with('error', 'Peserta tidak ditemukan dalam sesi ini');
            }
            
        } else {
            // Participant can only view their own matrix
            if (!$user || !$user->peserta) {
                return redirect()->route('participant.login')->with('error', 'Silakan login terlebih dahulu');
            }
            
            $peserta = $user->peserta;
            
            // Check if there's a specific session parameter
            $requestedSesiId = request()->query('sesi');
            if ($requestedSesiId) {
                // Get the specific session if participant is registered
                $sesi = SesiPenilaian::whereHas('participants', function($query) use ($peserta) {
                    $query->where('peserta_id', $peserta->id);
                })->where('id', $requestedSesiId)->where('status', 'active')->first();
            } else {
                // Get the active session for this participant
                $sesi = SesiPenilaian::whereHas('participants', function($query) use ($peserta) {
                    $query->where('peserta_id', $peserta->id);
                })->where('status', 'active')->first();
            }
            
            if (!$sesi) {
                return redirect()->route('peserta.dashboard')->with('error', 'Tidak ada sesi aktif untuk Anda');
            }
        }
        
        // Get in-tray assessment for this session
        $inTrayAssessment = $sesi->assessments()
            ->whereHas('penilaian', function($query) {
                $query->where('jenis', 'in_tray');
            })
            ->where('model_in_tray', 'prioritas')
            ->with('penilaian')
            ->first();
            
        if (!$inTrayAssessment) {
            $redirectRoute = $isAdmin ? 'admin.progress.index' : 'peserta.dashboard';
            return redirect()->route($redirectRoute)->with('error', 'Tidak ada assessment in-tray dengan mode prioritas untuk sesi ini');
        }
        
        // Get all memos for this assessment
        $memos = LatihanInTray::where('penilaian_id', $inTrayAssessment->penilaian_id)
            ->orderBy('id')
            ->get();
            
        // Get participant's answers with priorities
        $jawaban = JawabanInTray::where('peserta_id', $peserta->id)
            ->where('penilaian_id', $inTrayAssessment->penilaian_id)
            ->whereHas('prioritasMemo')
            ->with('prioritasMemo', 'latihanInTray')
            ->get();
            
        // Organize memos by priority matrix
        $matrix = [
            'mendesak_penting' => [],      // Quadrant 1: Urgent & Important
            'tidak_mendesak_penting' => [], // Quadrant 2: Not Urgent & Important
            'mendesak_tidak_penting' => [], // Quadrant 3: Urgent & Not Important
            'tidak_mendesak_tidak_penting' => [] // Quadrant 4: Not Urgent & Not Important
        ];
        
        foreach ($jawaban as $jawab) {
            $memo = $jawab->latihanInTray;
            $prioritas = $jawab->prioritasMemo;
            
            if ($memo && $prioritas) {
                $memoData = [
                    'id' => $memo->id,
                    'judul' => $memo->judul_memo,
                    'konten' => $memo->konten_memo,
                    'disposisi' => $jawab->disposisi,
                    'jawaban_pertanyaan' => $jawab->jawaban_pertanyaan,
                    'prioritas_label' => $prioritas->priority_label,
                    'kategori_prioritas' => $prioritas->kategori_prioritas
                ];
                
                // Categorize based on priority
                switch ($prioritas->kategori_prioritas) {
                    case 'mendesak_penting':
                        $matrix['mendesak_penting'][] = $memoData;
                        break;
                    case 'tidak_mendesak_penting':
                        $matrix['tidak_mendesak_penting'][] = $memoData;
                        break;
                    case 'mendesak_tidak_penting':
                        $matrix['mendesak_tidak_penting'][] = $memoData;
                        break;
                    case 'tidak_mendesak_tidak_penting':
                        $matrix['tidak_mendesak_tidak_penting'][] = $memoData;
                        break;
                }
            }
        }
        
        return view('intray.matrix', compact(
            'sesi', 
            'peserta', 
            'matrix', 
            'isAdmin',
            'inTrayAssessment'
        ));
    }
    
    /**
     * Get matrix data for AJAX requests
     */
    public function getMatrixData(Request $request, $sesiId, $pesertaId)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role === 'admin';
        
        if (!$isAdmin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $sesi = SesiPenilaian::findOrFail($sesiId);
        $peserta = Peserta::findOrFail($pesertaId);
        
        // Get in-tray assessment
        $inTrayAssessment = $sesi->assessments()
            ->whereHas('penilaian', function($query) {
                $query->where('jenis', 'in_tray');
            })
            ->where('model_in_tray', 'prioritas')
            ->with('penilaian')
            ->first();
            
        if (!$inTrayAssessment) {
            return response()->json(['error' => 'No in-tray assessment found'], 404);
        }
        
        // Get participant's answers
        $jawaban = JawabanInTray::where('peserta_id', $pesertaId)
            ->where('penilaian_id', $inTrayAssessment->penilaian_id)
            ->whereHas('prioritasMemo')
            ->with('prioritasMemo', 'latihanInTray')
            ->get();
            
        $matrix = [
            'mendesak_penting' => [],
            'tidak_mendesak_penting' => [],
            'mendesak_tidak_penting' => [],
            'tidak_mendesak_tidak_penting' => []
        ];
        
        foreach ($jawaban as $jawab) {
            $memo = $jawab->latihanInTray;
            $prioritas = $jawab->prioritasMemo;
            
            if ($memo && $prioritas) {
                $memoData = [
                    'id' => $memo->id,
                    'judul' => $memo->judul_memo,
                    'konten' => $memo->konten_memo,
                    'disposisi' => $jawab->disposisi,
                    'jawaban_pertanyaan' => $jawab->jawaban_pertanyaan,
                    'prioritas_label' => $prioritas->priority_label,
                    'kategori_prioritas' => $prioritas->kategori_prioritas
                ];
                
                $matrix[$prioritas->kategori_prioritas][] = $memoData;
            }
        }
        
        return response()->json([
            'matrix' => $matrix,
            'peserta' => $peserta->nama_lengkap,
            'sesi' => $sesi->nama
        ]);
    }
}
