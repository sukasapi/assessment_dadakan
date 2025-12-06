<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SesiPenilaian;
use App\Models\Penilaian;
use App\Models\ItemPenilaian;

class PenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil sesi penilaian yang sudah dibuat
        $sesiId = SesiPenilaian::first()->id;

        // Buat 4 jenis penilaian + 2 studi kasus terpisah (BQ dan PQ)
        $penilaianData = [
            [
                'nama' => 'Studi Kasus - Manajemen Konflik',
                'jenis' => 'studi_kasus',
                'petunjuk' => 'Baca dan analisis kasus berikut dengan seksama. Berikan jawaban yang komprehensif berdasarkan pemahaman Anda tentang manajemen konflik dan kepemimpinan.',
                'konten' => 'Anda adalah seorang Manager di departemen IT yang baru saja bergabung dengan perusahaan. Tim Anda terdiri dari 5 orang dengan berbagai latar belakang dan pengalaman. Beberapa minggu terakhir, Anda melihat ada ketegangan antara dua anggota tim senior yang saling bersaing untuk posisi Team Lead. Bagaimana Anda akan menangani situasi ini?',
                'durasi_menit' => 30,
                'urutan' => 1
            ],
            [
                'nama' => 'Studi Kasus BQ',
                'jenis' => 'studi_kasus',
                'petunjuk' => 'Baca dan analisis kasus berikut dengan seksama. Berikan jawaban yang komprehensif berdasarkan pemahaman Anda.',
                'konten' => 'Studi kasus untuk kategori BQ.',
                'durasi_menit' => 30,
                'urutan' => 1
            ],
            [
                'nama' => 'Studi Kasus PQ',
                'jenis' => 'studi_kasus',
                'petunjuk' => 'Baca dan analisis kasus berikut dengan seksama. Berikan jawaban yang komprehensif berdasarkan pemahaman Anda.',
                'konten' => 'Studi kasus untuk kategori PQ.',
                'durasi_menit' => 30,
                'urutan' => 1
            ],
            [
                'nama' => 'In-Tray Exercise - Prioritas Manajemen',
                'jenis' => 'in_tray',
                'petunjuk' => 'Anda adalah seorang Manager yang baru saja masuk kantor dan menemukan 10 memo di meja Anda. Urutkan memo-memo tersebut berdasarkan prioritas dan berikan disposisi untuk masing-masing memo.',
                'konten' => 'Latihan ini menguji kemampuan Anda dalam mengelola prioritas dan mengambil keputusan yang tepat dalam situasi yang menekan.',
                'durasi_menit' => 45,
                'urutan' => 2
            ],
            [
                'nama' => 'Role-Play - Negosiasi Tim',
                'jenis' => 'roleplay',
                'petunjuk' => 'Anda akan melakukan role-play sebagai seorang Team Leader yang harus memotivasi tim yang sedang mengalami penurunan performa.',
                'konten' => 'Skenario: Tim Anda telah gagal mencapai target selama 3 bulan berturut-turut. Beberapa anggota tim mulai kehilangan semangat dan ada yang ingin pindah ke tim lain. Bagaimana Anda akan memotivasi dan mempertahankan tim Anda?',
                'durasi_menit' => 20,
                'urutan' => 3
            ],
            [
                'nama' => 'FGD - Strategi Digitalisasi',
                'jenis' => 'fgd',
                'petunjuk' => 'Diskusikan dengan kelompok tentang strategi digitalisasi untuk meningkatkan efisiensi operasional perusahaan.',
                'konten' => 'Topik: Perusahaan Anda berencana melakukan digitalisasi dalam 2 tahun ke depan. Diskusikan aspek-aspek yang perlu diperhatikan, tantangan yang mungkin dihadapi, dan langkah-langkah implementasi yang efektif.',
                'durasi_menit' => 25,
                'urutan' => 4
            ]
        ];

        foreach ($penilaianData as $data) {
            $penilaian = Penilaian::create([
                'sesi_penilaian_id' => $sesiId,
                'nama' => $data['nama'],
                'jenis' => $data['jenis'],
                'petunjuk' => $data['petunjuk'],
                'konten' => $data['konten'],
                'durasi_menit' => $data['durasi_menit'],
                'urutan' => $data['urutan'],
                'aktif' => true
            ]);

            // Item penilaian akan dibuat oleh ItemPenilaianSeeder
        }
    }
}
