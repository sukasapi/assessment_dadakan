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
            'status' => 'required|in:draft,final'
        ]);

        $penilaian = Penilaian::findOrFail($penilaianId);
        if (!$penilaian->isActive()) {
            return response()->json(['error' => 'Penilaian tidak aktif'], 400);
        }

        $jawaban = JawabanStudiKasus::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId
            ],
            [
                'jawaban' => $request->jawaban,
                'status' => $request->status,
                'waktu_simpan' => now()
            ]
        );

        if ($request->status === 'final') {
            KemajuanPenilaian::where('peserta_id', $pesertaId)
                ->where('penilaian_id', $penilaianId)
                ->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now()
                ]);
        }

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
            'status' => 'required|in:draft,final'
        ]);

        $penilaian = Penilaian::findOrFail($penilaianId);
        if (!$penilaian->isActive()) {
            return response()->json(['error' => 'Penilaian tidak aktif'], 400);
        }

        // Hapus jawaban lama
        JawabanInTray::where('peserta_id', $pesertaId)
            ->where('penilaian_id', $penilaianId)
            ->delete();

        // Simpan jawaban baru
        foreach ($request->jawaban as $jawaban) {
            JawabanInTray::create([
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId,
                'latihan_in_tray_id' => $jawaban['latihan_in_tray_id'],
                'urutan_prioritas' => $jawaban['urutan_prioritas'],
                'disposisi' => $jawaban['disposisi'] ?? '',
                'status' => $request->status,
                'waktu_simpan' => now()
            ]);
        }

        if ($request->status === 'final') {
            KemajuanPenilaian::where('peserta_id', $pesertaId)
                ->where('penilaian_id', $penilaianId)
                ->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now()
                ]);
        }

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
            'status' => 'required|in:draft,final'
        ]);

        $penilaian = Penilaian::findOrFail($penilaianId);
        if (!$penilaian->isActive()) {
            return response()->json(['error' => 'Penilaian tidak aktif'], 400);
        }

        $catatan = CatatanRoleplay::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId
            ],
            [
                'catatan' => $request->catatan,
                'status' => $request->status,
                'waktu_simpan' => now()
            ]
        );

        if ($request->status === 'final') {
            KemajuanPenilaian::where('peserta_id', $pesertaId)
                ->where('penilaian_id', $penilaianId)
                ->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now()
                ]);
        }

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
            'status' => 'required|in:draft,final'
        ]);

        $penilaian = Penilaian::findOrFail($penilaianId);
        if (!$penilaian->isActive()) {
            return response()->json(['error' => 'Penilaian tidak aktif'], 400);
        }

        $catatan = CatatanFgd::updateOrCreate(
            [
                'peserta_id' => $pesertaId,
                'penilaian_id' => $penilaianId
            ],
            [
                'catatan' => $request->catatan,
                'status' => $request->status,
                'waktu_simpan' => now()
            ]
        );

        if ($request->status === 'final') {
            KemajuanPenilaian::where('peserta_id', $pesertaId)
                ->where('penilaian_id', $penilaianId)
                ->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now()
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil disimpan',
            'data' => $catatan
        ]);
    }
}
