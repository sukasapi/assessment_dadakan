<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriStudiKasus;
use App\Models\AspekPenilaianStudiKasus;

class AspekPenilaianStudiKasusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriPQ = KategoriStudiKasus::where('kode', 'PQ')->first();
        $kategoriBQ = KategoriStudiKasus::where('kode', 'BQ')->first();

        if (!$kategoriPQ || !$kategoriBQ) {
            $this->command->error('Kategori PQ atau BQ belum ada. Jalankan KategoriStudiKasusSeeder terlebih dahulu.');
            return;
        }

        // 6 Aspek Penilaian untuk PQ
        $aspekPQ = [
            [
                'nomor' => 1,
                'pertanyaan' => 'Apakah jawaban asesi sudah terstruktur (urut dan berkesinambungan) dan rapi secara tampilan?',
                'urutan' => 1
            ],
            [
                'nomor' => 2,
                'pertanyaan' => 'Apakah asesi mampu mengidentifikasi masalah pada soal?',
                'urutan' => 2
            ],
            [
                'nomor' => 3,
                'pertanyaan' => 'Apakah asesi mampu memberikan strategi penyelesaian masalah?',
                'urutan' => 3
            ],
            [
                'nomor' => 4,
                'pertanyaan' => 'Apakah asesi mampu merumuskan langkah-langkah eksekusi atau program utama dalam mewujudkan strategi penyelesaian masalah?',
                'urutan' => 4
            ],
            [
                'nomor' => 5,
                'pertanyaan' => 'Apakah asesi mampu membuat mitigasi risiko atas program utama?',
                'urutan' => 5
            ],
            [
                'nomor' => 6,
                'pertanyaan' => 'Apakah asesi telah memanfaatkan informasi keuangan pada jawabannya?',
                'urutan' => 6
            ]
        ];

        foreach ($aspekPQ as $aspek) {
            AspekPenilaianStudiKasus::updateOrCreate(
                [
                    'kategori_studi_kasus_id' => $kategoriPQ->id,
                    'nomor' => $aspek['nomor']
                ],
                [
                    'pertanyaan' => $aspek['pertanyaan'],
                    'urutan' => $aspek['urutan'],
                    'aktif' => true
                ]
            );
        }

        // 6 Aspek Penilaian untuk BQ (sama dengan PQ)
        foreach ($aspekPQ as $aspek) {
            AspekPenilaianStudiKasus::updateOrCreate(
                [
                    'kategori_studi_kasus_id' => $kategoriBQ->id,
                    'nomor' => $aspek['nomor']
                ],
                [
                    'pertanyaan' => $aspek['pertanyaan'],
                    'urutan' => $aspek['urutan'],
                    'aktif' => true
                ]
            );
        }
    }
}
