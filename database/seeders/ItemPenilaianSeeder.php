<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemPenilaian;
use App\Models\Penilaian;

class ItemPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get penilaian IDs
        $studiKasus = Penilaian::where('jenis', 'studi_kasus')->first();
        $inTray = Penilaian::where('jenis', 'in_tray')->first();
        $roleplay = Penilaian::where('jenis', 'roleplay')->first();
        $fgd = Penilaian::where('jenis', 'fgd')->first();

        if ($studiKasus) {
            // Item untuk Studi Kasus
            ItemPenilaian::create([
                'penilaian_id' => $studiKasus->id,
                'judul' => 'Analisis Situasi Perusahaan',
                'konten' => 'Anda adalah seorang manajer yang baru saja bergabung dengan perusahaan XYZ. Perusahaan ini mengalami penurunan kinerja selama 6 bulan terakhir. Analisis situasi dan berikan rekomendasi strategis untuk mengatasi masalah tersebut.',
                'petunjuk' => 'Berikan analisis yang sistematis dengan pendekatan SWOT dan rekomendasi yang konkret dan dapat diimplementasikan.',
                'jenis' => 'studi_kasus',
                'urutan' => 1,
            ]);

            ItemPenilaian::create([
                'penilaian_id' => $studiKasus->id,
                'judul' => 'Manajemen Konflik Tim',
                'konten' => 'Tim Anda mengalami konflik internal antara dua anggota kunci. Konflik ini berdampak pada produktivitas dan moral tim. Bagaimana Anda akan mengatasi situasi ini?',
                'petunjuk' => 'Gunakan pendekatan win-win solution dan tunjukkan kemampuan mediasi dan resolusi konflik.',
                'jenis' => 'studi_kasus',
                'urutan' => 2,
            ]);
        }

        if ($inTray) {
            // Item untuk In-Tray Exercise
            ItemPenilaian::create([
                'penilaian_id' => $inTray->id,
                'judul' => 'Memo Prioritas',
                'konten' => 'Anda memiliki 10 memo yang harus diproses dalam waktu 2 jam. Setiap memo memiliki tingkat urgensi dan kepentingan yang berbeda.',
                'petunjuk' => 'Urutkan memo berdasarkan prioritas dan berikan disposisi yang tepat untuk setiap memo.',
                'jenis' => 'in_tray',
                'urutan' => 1,
            ]);
        }

        if ($roleplay) {
            // Item untuk Roleplay
            ItemPenilaian::create([
                'penilaian_id' => $roleplay->id,
                'judul' => 'Presentasi kepada Direksi',
                'konten' => 'Anda diminta untuk mempresentasikan proposal proyek baru kepada direksi perusahaan. Presentasikan dengan meyakinkan dan siap menghadapi pertanyaan kritis.',
                'petunjuk' => 'Fokus pada value proposition, feasibility, dan return on investment dari proyek yang diusulkan.',
                'jenis' => 'roleplay',
                'urutan' => 1,
            ]);

            ItemPenilaian::create([
                'penilaian_id' => $roleplay->id,
                'judul' => 'Negosiasi dengan Vendor',
                'konten' => 'Lakukan negosiasi dengan vendor untuk mendapatkan harga terbaik untuk pembelian peralatan kantor senilai Rp 500 juta.',
                'petunjuk' => 'Gunakan teknik negosiasi yang efektif, siapkan BATNA, dan capai kesepakatan yang menguntungkan kedua belah pihak.',
                'jenis' => 'roleplay',
                'urutan' => 2,
            ]);
        }

        if ($fgd) {
            // Item untuk FGD
            ItemPenilaian::create([
                'penilaian_id' => $fgd->id,
                'judul' => 'Strategi Digital Transformation',
                'konten' => 'Diskusikan strategi digital transformation untuk perusahaan tradisional yang ingin bertransformasi menjadi perusahaan digital.',
                'petunjuk' => 'Berikan kontribusi yang konstruktif, dengarkan pendapat orang lain, dan bangun konsensus dalam kelompok.',
                'jenis' => 'fgd',
                'urutan' => 1,
            ]);

            ItemPenilaian::create([
                'penilaian_id' => $fgd->id,
                'judul' => 'Work-Life Balance di Era Digital',
                'konten' => 'Diskusikan tantangan dan solusi untuk menjaga work-life balance di era digital yang serba cepat dan terhubung.',
                'petunjuk' => 'Berikan perspektif yang beragam dan solusi yang praktis untuk diterapkan dalam organisasi.',
                'jenis' => 'fgd',
                'urutan' => 2,
            ]);
        }
    }
}
