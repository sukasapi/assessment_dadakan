<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JawabanStudiKasus;
use App\Models\JawabanInTray;
use App\Models\CatatanRoleplay;
use App\Models\CatatanFgd;
use App\Models\LatihanInTray;
use App\Models\Peserta;
use App\Models\Penilaian;
use Carbon\Carbon;

class JawabanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedJawabanStudiKasus();
        $this->seedJawabanInTray();
        $this->seedCatatanRoleplay();
        $this->seedCatatanFgd();
    }

    private function seedJawabanStudiKasus()
    {
        $studiKasus = Penilaian::where('jenis', 'studi_kasus')->first();
        $peserta = Peserta::all();

        if ($studiKasus) {
            foreach ($peserta as $p) {
                // 70% chance peserta sudah menjawab
                if (rand(1, 100) <= 70) {
                    JawabanStudiKasus::create([
                        'peserta_id' => $p->id,
                        'penilaian_id' => $studiKasus->id,
                        'jawaban' => $this->getStudiKasusJawaban(),
                        'status' => rand(1, 100) <= 80 ? 'final' : 'draft',
                        'waktu_simpan' => Carbon::now()->subMinutes(rand(1, 120)),
                    ]);
                }
            }
        }
    }

    private function seedJawabanInTray()
    {
        $inTray = Penilaian::where('jenis', 'in_tray')->first();
        $peserta = Peserta::all();

        if ($inTray) {
            // Create in-tray exercises
            $latihan1 = LatihanInTray::create([
                'penilaian_id' => $inTray->id,
                'konten_memo' => 'Memo dari HRD: Permintaan approval cuti massal untuk 5 karyawan selama libur Lebaran. Deadline: 2 hari lagi.',
                'urutan' => 1,
            ]);

            $latihan2 = LatihanInTray::create([
                'penilaian_id' => $inTray->id,
                'konten_memo' => 'Memo dari Finance: Laporan keuangan bulanan sudah siap untuk review. Perlu approval sebelum 5 hari kerja.',
                'urutan' => 2,
            ]);

            $latihan3 = LatihanInTray::create([
                'penilaian_id' => $inTray->id,
                'konten_memo' => 'Memo dari IT: Server mengalami gangguan dan memerlukan maintenance. Estimasi downtime: 4 jam.',
                'urutan' => 3,
            ]);

            $latihan4 = LatihanInTray::create([
                'penilaian_id' => $inTray->id,
                'konten_memo' => 'Memo dari Marketing: Proposal kampanye Q4 perlu approval budget. Total anggaran: Rp 200 juta.',
                'urutan' => 4,
            ]);

            $latihan5 = LatihanInTray::create([
                'penilaian_id' => $inTray->id,
                'konten_memo' => 'Memo dari Operations: Keluhan customer service meningkat 30%. Perlu strategi perbaikan segera.',
                'urutan' => 5,
            ]);

            foreach ($peserta as $p) {
                // 60% chance peserta sudah menjawab
                if (rand(1, 100) <= 60) {
                    $latihan = [$latihan1, $latihan2, $latihan3, $latihan4, $latihan5];
                    
                    foreach ($latihan as $index => $l) {
                        JawabanInTray::create([
                            'peserta_id' => $p->id,
                            'penilaian_id' => $inTray->id,
                            'latihan_in_tray_id' => $l->id,
                            'urutan_prioritas' => $index + 1,
                            'disposisi' => $this->getInTrayDisposisi(),
                            'status' => rand(1, 100) <= 70 ? 'final' : 'draft',
                            'waktu_simpan' => Carbon::now()->subMinutes(rand(1, 90)),
                        ]);
                    }
                }
            }
        }
    }

    private function seedCatatanRoleplay()
    {
        $roleplay = Penilaian::where('jenis', 'roleplay')->first();
        $peserta = Peserta::all();

        if ($roleplay) {
            foreach ($peserta as $p) {
                // 50% chance peserta sudah melakukan roleplay
                if (rand(1, 100) <= 50) {
                    CatatanRoleplay::create([
                        'peserta_id' => $p->id,
                        'penilaian_id' => $roleplay->id,
                        'catatan' => $this->getRoleplayCatatan(),
                        'status' => rand(1, 100) <= 60 ? 'final' : 'draft',
                        'waktu_simpan' => Carbon::now()->subMinutes(rand(1, 60)),
                    ]);
                }
            }
        }
    }

    private function seedCatatanFgd()
    {
        $fgd = Penilaian::where('jenis', 'fgd')->first();
        $peserta = Peserta::all();

        if ($fgd) {
            foreach ($peserta as $p) {
                // 40% chance peserta sudah berpartisipasi FGD
                if (rand(1, 100) <= 40) {
                    CatatanFgd::create([
                        'peserta_id' => $p->id,
                        'penilaian_id' => $fgd->id,
                        'catatan' => $this->getFgdCatatan(),
                        'status' => rand(1, 100) <= 50 ? 'final' : 'draft',
                        'waktu_simpan' => Carbon::now()->subMinutes(rand(1, 45)),
                    ]);
                }
            }
        }
    }

    private function getStudiKasusJawaban()
    {
        $jawaban = [
            'Berdasarkan analisis situasi perusahaan XYZ, saya mengidentifikasi beberapa masalah utama: penurunan penjualan 25%, turnover karyawan tinggi, dan inefisiensi operasional. Pendekatan SWOT menunjukkan kelemahan dalam sistem manajemen dan ancaman dari kompetitor. Rekomendasi: restrukturisasi tim sales, implementasi sistem CRM, dan program retensi karyawan.',
            'Situasi konflik tim memerlukan pendekatan sistematis. Saya akan mengadakan pertemuan individual dengan kedua pihak untuk memahami akar masalah, kemudian fasilitasi diskusi terbuka dengan fokus pada solusi. Implementasi sistem komunikasi yang lebih baik dan pembentukan tim building activities untuk memperbaiki hubungan kerja.',
            'Analisis menunjukkan bahwa masalah utama adalah kurangnya koordinasi antar departemen dan sistem monitoring yang tidak efektif. Saya merekomendasikan pembentukan cross-functional team, implementasi KPI yang terukur, dan pelatihan leadership untuk middle management.',
        ];
        
        return $jawaban[array_rand($jawaban)];
    }

    private function getInTrayDisposisi()
    {
        $disposisi = [
            'Setujui dengan catatan: koordinasikan dengan supervisor terkait dan pastikan coverage kerja tetap optimal.',
            'Setujui dan minta laporan evaluasi setelah implementasi untuk assessment efektivitas.',
            'Setujui dengan syarat: presentasikan proposal detail termasuk timeline dan resource yang dibutuhkan.',
            'Setujui dengan monitoring berkala setiap minggu untuk memastikan target tercapai.',
            'Setujui dan minta feedback dari stakeholder terkait sebelum finalisasi.',
        ];
        
        return $disposisi[array_rand($disposisi)];
    }

    private function getRoleplayCatatan()
    {
        $catatan = [
            'Peserta menunjukkan kemampuan presentasi yang baik dengan struktur yang jelas. Namun perlu meningkatkan confidence dan handling pertanyaan kritis. Skor: 7/10',
            'Kemampuan negosiasi cukup baik dengan preparation yang matang. Bisa lebih agresif dalam bargaining dan menunjukkan alternatif yang lebih menarik. Skor: 8/10',
            'Presentasi berjalan lancar dengan konten yang informatif. Perlu latihan lebih untuk mengatasi nervous dan meningkatkan engagement dengan audience. Skor: 6/10',
        ];
        
        return $catatan[array_rand($catatan)];
    }

    private function getFgdCatatan()
    {
        $catatan = [
            'Peserta aktif memberikan kontribusi ide-ide inovatif. Kemampuan mendengarkan dan membangun konsensus sangat baik. Perlu lebih berani dalam challenging asumsi yang ada.',
            'Partisipasi dalam diskusi cukup baik dengan perspektif yang beragam. Namun perlu meningkatkan kemampuan dalam mengelola konflik pendapat dan memfasilitasi diskusi yang produktif.',
            'Kontribusi dalam FGD menunjukkan pemahaman yang mendalam tentang topik. Kemampuan analisis dan sintesis sangat baik. Perlu latihan lebih dalam public speaking.',
        ];
        
        return $catatan[array_rand($catatan)];
    }
}
