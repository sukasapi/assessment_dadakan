<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\SesiPenilaian;
use App\Models\Penilaian;
use App\Models\KemajuanPenilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PesertaController extends Controller
{
    public function showLogin()
    {
        return view('peserta.login');
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
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('peserta.login');
        }

        $peserta = Peserta::findOrFail($pesertaId);
        
        // Ambil sesi penilaian aktif
        $sesiAktif = SesiPenilaian::where('aktif', true)
                                  ->where('status', 'active')
                                  ->first();

        // Ambil semua penilaian untuk sesi aktif
        $penilaian = collect();
        if ($sesiAktif) {
            $penilaian = Penilaian::where('sesi_penilaian_id', $sesiAktif->id)
                                  ->where('aktif', true)
                                  ->orderBy('urutan')
                                  ->get();
        }

        // Ambil progress peserta
        $progress = KemajuanPenilaian::where('peserta_id', $pesertaId)
                                    ->pluck('status', 'penilaian_id')
                                    ->toArray();

        return view('peserta.dashboard', compact('peserta', 'sesiAktif', 'penilaian', 'progress'));
    }

    public function showPenilaian($penilaianId)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('peserta.login');
        }

        $penilaian = Penilaian::findOrFail($penilaianId);
        
        // Cek apakah penilaian aktif
        if (!$penilaian->isActive()) {
            return redirect()->route('peserta.dashboard')
                           ->withErrors(['error' => 'Penilaian tidak aktif.']);
        }

        // Buat atau update progress
        KemajuanPenilaian::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId
            ],
            [
                'status' => 'sedang_berlangsung',
                'aktivitas_terakhir' => now()
            ]
        );

        return view('peserta.penilaian', compact('penilaian'));
    }

    public function showBiodata()
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('peserta.login');
        }

        $peserta = Peserta::findOrFail($pesertaId);
        return view('peserta.biodata', compact('peserta'));
    }

    public function logout()
    {
        session()->forget(['peserta_id', 'peserta_name']);
        return redirect()->route('peserta.login');
    }
}
