<?php

namespace App\Http\Controllers;

use App\Models\JawabanStudiKasus;
use App\Models\JawabanInTray;
use App\Models\CatatanRoleplay;
use App\Models\CatatanFgd;
use App\Models\KemajuanPenilaian;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function saveJawabanStudiKasus(Request $request, $penilaianId)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'jawaban' => 'required|string',
            'status' => 'required|in:draft,final',
            'sesi_penilaian_id' => 'required|integer|exists:sesi_penilaian,id'
        ]);

        $penilaian = Penilaian::findOrFail($penilaianId);
        if (!$penilaian->isActive()) {
            return response()->json(['error' => 'Penilaian tidak aktif'], 400);
        }

        // Get sesi_penilaian_id from request (sesi yang sedang diakses user)
        $sesiPenilaianId = $request->sesi_penilaian_id;

        $jawaban = JawabanStudiKasus::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId,
                'sesi_penilaian_id' => $sesiPenilaianId
            ],
            [
                'jawaban' => $request->jawaban,
                'status' => $request->status,
                'waktu_simpan' => now(),
                'sesi_penilaian_id' => $sesiPenilaianId
            ]
        );

        // Update status kemajuan penilaian
        $statusKemajuan = $request->status === 'final' ? 'selesai' : 'sedang_berlangsung';
        
        KemajuanPenilaian::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId,
                'sesi_penilaian_id' => $sesiPenilaianId
            ],
            [
                'status' => $statusKemajuan,
                'waktu_selesai' => $request->status === 'final' ? now() : null,
                'aktivitas_terakhir' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Jawaban berhasil disimpan',
            'data' => $jawaban
        ]);
    }

    public function saveJawabanInTray(Request $request, $penilaianId)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Terima baik JSON maupun form-urlencoded
        $payload = $request->all();
        if (empty($payload) || !$request->has('jawaban')) {
            $json = json_decode($request->getContent(), true);
            if (is_array($json)) {
                $payload = $json;
                $request->merge($payload);
            }
        }

        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*.latihan_in_tray_id' => 'required|exists:latihan_in_tray,id',
            'jawaban.*.urutan_prioritas' => 'required|integer|min:1',
            'jawaban.*.disposisi' => 'nullable|string',
            'jawaban.*.kategori_prioritas' => 'nullable|in:mendesak_penting,mendesak_tidak_penting,tidak_mendesak_penting,tidak_mendesak_tidak_penting',
            'jawaban.*.jawaban_pertanyaan' => 'nullable|string',
            'status' => 'required|in:draft,final',
            'sesi_penilaian_id' => 'required|integer|exists:sesi_penilaian,id'
        ]);

        $penilaian = Penilaian::findOrFail($penilaianId);
        if (!$penilaian->isActive()) {
            return response()->json(['error' => 'Penilaian tidak aktif'], 400);
        }

        // Ambil sesi_penilaian_id dari request (sesi yang sedang diakses user)
        $sesiPenilaianId = $request->sesi_penilaian_id;
        
        // Jika status adalah 'final', update semua jawaban existing menjadi 'final' terlebih dahulu
        if ($request->status === 'final') {
            JawabanInTray::where('peserta_id', $pesertaId)
                ->where('penilaian_id', $penilaianId)
                ->where('sesi_penilaian_id', $sesiPenilaianId)
                ->update([
                    'status' => 'final',
                    'waktu_simpan' => now()
                ]);
        }

        // Hapus jawaban lama
        JawabanInTray::where('peserta_id', $pesertaId)
            ->where('penilaian_id', $penilaianId)
            ->where('sesi_penilaian_id', $sesiPenilaianId)
            ->delete();

        // Simpan jawaban baru
        foreach ($request->jawaban as $jawaban) {
            $jawabanInTray = JawabanInTray::create([
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId,
                'sesi_penilaian_id' => $sesiPenilaianId,
                'latihan_in_tray_id' => $jawaban['latihan_in_tray_id'],
                'urutan_prioritas' => $jawaban['urutan_prioritas'],
                'disposisi' => $jawaban['disposisi'] ?? '',
                'status' => $request->status,
                'waktu_simpan' => now(),
                'model_assessment' => $sesiAssessment->model_in_tray ?? 'urutan',
                'jawaban_pertanyaan' => $jawaban['jawaban_pertanyaan'] ?? null
            ]);

            // Simpan prioritas jika menggunakan model prioritas
            if (isset($jawaban['kategori_prioritas']) && !empty($jawaban['kategori_prioritas'])) {
                \App\Models\PrioritasMemo::updateOrCreate(
                    ['jawaban_in_tray_id' => $jawabanInTray->id],
                    ['kategori_prioritas' => $jawaban['kategori_prioritas']]
                );
            }
        }

        // Update status kemajuan penilaian berdasarkan status semua jawaban In-Tray
        // Cek semua jawaban In-Tray untuk peserta, penilaian, dan sesi ini
        $allJawabanInTray = JawabanInTray::where('peserta_id', $pesertaId)
            ->where('penilaian_id', $penilaianId)
            ->where('sesi_penilaian_id', $sesiPenilaianId)
            ->get();
        
        // Jika semua jawaban memiliki status 'final', maka kemajuan penilaian = 'selesai'
        // Jika ada yang 'draft', maka kemajuan penilaian = 'sedang_berlangsung'
        $statusKemajuan = 'sedang_berlangsung'; // default
        if ($allJawabanInTray->isNotEmpty() && $allJawabanInTray->every(function($jawaban) {
            return $jawaban->status === 'final';
        })) {
            $statusKemajuan = 'selesai';
        }
        
        KemajuanPenilaian::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId,
                'sesi_penilaian_id' => $sesiPenilaianId
            ],
            [
                'status' => $statusKemajuan,
                'waktu_selesai' => $statusKemajuan === 'selesai' ? now() : null,
                'aktivitas_terakhir' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Jawaban berhasil disimpan',
        ]);
    }

    public function saveCatatanRoleplay(Request $request, $penilaianId)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'catatan' => 'required|string',
            'status' => 'required|in:draft,final',
            'sesi_penilaian_id' => 'required|integer|exists:sesi_penilaian,id'
        ]);

        $penilaian = Penilaian::findOrFail($penilaianId);
        if (!$penilaian->isActive()) {
            return response()->json(['error' => 'Penilaian tidak aktif'], 400);
        }

        $sesiPenilaianId = $request->sesi_penilaian_id;
        $catatan = CatatanRoleplay::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId,
                'sesi_penilaian_id' => $sesiPenilaianId
            ],
            [
                'catatan' => $request->catatan,
                'status' => $request->status,
                'waktu_simpan' => now()
            ]
        );

        // Update status kemajuan penilaian
        $statusKemajuan = $request->status === 'final' ? 'selesai' : 'sedang_berlangsung';
        
        KemajuanPenilaian::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId,
                'sesi_penilaian_id' => $sesiPenilaianId
            ],
            [
                'status' => $statusKemajuan,
                'waktu_selesai' => $request->status === 'final' ? now() : null,
                'aktivitas_terakhir' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil disimpan',
            'data' => $catatan
        ]);
    }

    public function saveCatatanFgd(Request $request, $penilaianId)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'catatan' => 'required|string',
            'status' => 'required|in:draft,final',
            'sesi_penilaian_id' => 'required|integer|exists:sesi_penilaian,id'
        ]);

        $penilaian = Penilaian::findOrFail($penilaianId);
        if (!$penilaian->isActive()) {
            return response()->json(['error' => 'Penilaian tidak aktif'], 400);
        }

        $sesiPenilaianId = $request->sesi_penilaian_id;
        $catatan = CatatanFgd::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId,
                'sesi_penilaian_id' => $sesiPenilaianId
            ],
            [
                'catatan' => $request->catatan,
                'status' => $request->status,
                'waktu_simpan' => now()
            ]
        );

        // Update status kemajuan penilaian
        $statusKemajuan = $request->status === 'final' ? 'selesai' : 'sedang_berlangsung';
        
        KemajuanPenilaian::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId,
                'sesi_penilaian_id' => $sesiPenilaianId
            ],
            [
                'status' => $statusKemajuan,
                'waktu_selesai' => $request->status === 'final' ? now() : null,
                'aktivitas_terakhir' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil disimpan',
            'data' => $catatan
        ]);
    }
}
