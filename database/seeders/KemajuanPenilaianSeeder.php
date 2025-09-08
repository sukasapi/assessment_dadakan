<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KemajuanPenilaian;
use App\Models\Peserta;
use App\Models\Penilaian;
use Carbon\Carbon;

class KemajuanPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $peserta = Peserta::all();
        $penilaian = Penilaian::all();

        foreach ($peserta as $p) {
            foreach ($penilaian as $pen) {
                $status = $this->getRandomStatus();
                $waktuMulai = null;
                $waktuSelesai = null;
                $aktivitasTerakhir = null;

                if ($status === 'sedang_berlangsung') {
                    $waktuMulai = Carbon::now()->subMinutes(rand(10, 45));
                    $aktivitasTerakhir = Carbon::now()->subMinutes(rand(1, 9));
                } elseif ($status === 'selesai') {
                    $waktuMulai = Carbon::now()->subMinutes(rand(60, 120));
                    $waktuSelesai = Carbon::now()->subMinutes(rand(1, 59));
                    $aktivitasTerakhir = $waktuSelesai;
                }

                KemajuanPenilaian::create([
                    'peserta_id' => $p->id,
                    'penilaian_id' => $pen->id,
                    'status' => $status,
                    'waktu_mulai' => $waktuMulai,
                    'waktu_selesai' => $waktuSelesai,
                    'aktivitas_terakhir' => $aktivitasTerakhir,
                ]);
            }
        }
    }

    private function getRandomStatus()
    {
        $statuses = ['belum_mulai', 'sedang_berlangsung', 'selesai'];
        $weights = [30, 40, 30]; // 30% belum mulai, 40% sedang berlangsung, 30% selesai
        
        $random = rand(1, 100);
        $cumulative = 0;
        
        for ($i = 0; $i < count($statuses); $i++) {
            $cumulative += $weights[$i];
            if ($random <= $cumulative) {
                return $statuses[$i];
            }
        }
        
        return 'belum_mulai';
    }
}
