<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SesiPenilaian;

class SesiPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SesiPenilaian::create([
            'nama' => 'Assessment Center Batch 1 - 2024',
            'status' => 'pending',
            'durasi_menit' => 120,
            'catatan' => 'Assessment Center untuk posisi Manager dan Supervisor',
            'aktif' => true
        ]);
    }
}
