<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriStudiKasus;
use App\Models\AspekPenilaianStudiKasus;
use App\Models\LevelPenilaianStudiKasus;

class LevelPenilaianStudiKasusSeeder extends Seeder
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

        // Data level untuk setiap aspek penilaian
        $levelData = [
            // Aspek 1: Struktur dan Kerapian
            1 => [
                0 => [
                    'deskripsi_level' => 'Belum mengerjakan',
                    'text_report' => 'Asesi belum menjawab'
                ],
                1 => [
                    'deskripsi_level' => 'Jawaban asesi belum terstruktur (urut dan berkesinambungan) dan rapi',
                    'text_report' => 'Jawaban yang dituliskan oleh Asesi belum memiliki struktur yang jelas, artinya jawaban Asesi belum secara runtut dijabarkan atau penulisan jawaban Asesi belum memiliki kesinambungan alur berpikir. Asesi perlu meningkatkan kemampuan dalam menuliskan gagasan melalui tulisan sehingga jawaban Asesi dapat mudah dipahami Asesor. Asesi dapat memanfaatkan penggunaan fitur numbering, pointing, bold, dan spasi yang jelas dan rapi untuk membantu menegaskan gagasan atau jawaban.'
                ],
                2 => [
                    'deskripsi_level' => 'Jawaban asesi terstruktur (urut dan berkesinambungan) namun belum rapi',
                    'text_report' => 'Jawaban yang dituliskan oleh Asesi telah terstruktur, artinya jawaban Asesi sudah secara runtut dijabarkan atau penulisan jawaban Asesi sudah memiliki kesinambungan alur berpikir. Asesi perlu meningkatkan kerapian dalam menuliskan gagasan melalui tulisan sehingga jawaban Asesi dapat mudah dipahami Asesor. Asesi dapat memanfaatkan penggunaan fitur numbering, pointing, bold, dan spasi yang jelas dan rapi untuk membantu menegaskan gagasan atau jawaban.'
                ],
                3 => [
                    'deskripsi_level' => 'Jawaban asesi (urut dan berkesinambungan) dan sudah rapi',
                    'text_report' => 'Jawaban yang dituliskan oleh Asesi telah terstruktur dan rapi, artinya jawaban Asesi sudah secara runtut dijabarkan atau penulisan jawaban Asesi sudah memiliki kesinambungan alur berpikir. Asesi telah memanfaatkan penggunaan fitur numbering, pointing, bold, dan spasi yang jelas dan rapi untuk membantu menegaskan gagasan atau jawaban.'
                ]
            ],
            // Aspek 2: Identifikasi Masalah
            2 => [
                0 => [
                    'deskripsi_level' => 'Belum dapat mengidentifikasi masalah',
                    'text_report' => 'Asesi belum dapat mengidentifikasi masalah'
                ],
                1 => [
                    'deskripsi_level' => 'Asesi mampu mengidentifikasi masalah secara umum',
                    'text_report' => 'Asesi mampu menunjukkan masalah secara umum. Akan tetapi, jawaban Asesi belum secara elaboratif menjelaskan apa masalah yang dihadapi. Asesi dapat melengkapi jawaban dengan disertai data maupun memanfaatkan tools analisis.'
                ],
                2 => [
                    'deskripsi_level' => 'Asesi mampu mengidentifikasi dan mengelaborasi masalah secara lebih detail',
                    'text_report' => 'Asesi mampu menunjukkan masalah secara elaboratif, artinya menjelaskan masalah yang ada pada soal disertai dengan data-data yang relevan dan selaras dengan permasalahan yang dimaksud. Asesi dapat memperkuat identifikasi masalah dengan memanfaatkan tools analisis.'
                ],
                3 => [
                    'deskripsi_level' => 'Asesi mampu mengidentifikasi dan mengelaborasi masalah dan akar permasalahan serta menggunakan metode tools yang sesuai',
                    'text_report' => 'Asesi mampu menunjukkan masalah secara elaboratif, artinya menjelaskan masalah sampai pada akar permasalahan yang ada pada soal disertai dengan data-data yang relevan dan selaras. Asesi juga memperkuat identifikasi masalah dengan memanfaatkan tools analisis yang sesuai.'
                ]
            ],
            // Aspek 3: Strategi Penyelesaian Masalah
            3 => [
                0 => [
                    'deskripsi_level' => 'Belum dapat memberikan strategi yang relevan untuk penyelesaian masalah',
                    'text_report' => 'Asesi belum dapat memberikan strategi yang relevan untuk penyelesaian masalah'
                ],
                1 => [
                    'deskripsi_level' => 'Asesi telah mampu mengusulkan strategi yang untuk penyelesaian masalah',
                    'text_report' => 'Secara umum sesuai dengan permasalahan yang diangkat. Akan tetapi, asesi belum mengelaborasikan lebih lanjut menggunakan data atau asumsi tertentu. Selain itu, Asesi juga dapat memanfaatkan tools analisis dalam merumuskan strategi penyelesaian masalah.'
                ],
                2 => [
                    'deskripsi_level' => 'Asesi telah mampu menyebutkan dan mengelaborasi strategi yang relevan untuk penyelesaian masalah',
                    'text_report' => 'Asesi telah mampu menyebutkan dan mengelaborasi strategi yang relevan untuk penyelesaian masalah dengan menggunakan data atau asumsi tertentu. Asesi juga dapat memanfaatkan tools analisis dalam merumuskan strategi penyelesaian masalah.'
                ],
                3 => [
                    'deskripsi_level' => 'Asesi telah mampu menyebutkan dan mengelaborasi strategi yang relevan untuk penyelesaian masalah pada berbagai bidang/area kerja dengan metode tools yang sesuai',
                    'text_report' => 'Asesi telah mampu menyebutkan dan mengelaborasi strategi yang relevan untuk penyelesaian masalah pada berbagai bidang/area kerja dengan menggunakan data atau asumsi tertentu. Asesi juga telah memanfaatkan tools analisis yang sesuai dalam merumuskan strategi penyelesaian masalah.'
                ]
            ],
            // Aspek 4: Langkah-langkah Eksekusi
            4 => [
                0 => [
                    'deskripsi_level' => 'Belum dapat merumuskan langkah-langkah eksekusi atau program utama',
                    'text_report' => 'Asesi belum dapat merumuskan langkah-langkah eksekusi atau program utama'
                ],
                1 => [
                    'deskripsi_level' => 'Asesi telah mampu merumuskan langkah-langkah eksekusi secara sederhana',
                    'text_report' => 'Asesi telah mampu merumuskan langkah-langkah eksekusi secara sederhana. Namun, langkah-langkah tersebut belum secara detail dijabarkan dan belum selaras dengan strategi penyelesaian masalah yang diusulkan.'
                ],
                2 => [
                    'deskripsi_level' => 'Asesi telah mampu merumuskan langkah-langkah eksekusi atau program utama secara runtut dan selaras dengan strategi penyelesaian masalah',
                    'text_report' => 'Asesi telah mampu merumuskan langkah-langkah eksekusi atau program utama secara runtut dan selaras dengan strategi penyelesaian masalah. Namun, langkah-langkah tersebut dapat lebih detail dijabarkan untuk berbagai area/bidang kerja.'
                ],
                3 => [
                    'deskripsi_level' => 'Asesi telah mampu merumuskan langkah-langkah eksekusi atau program utama secara runtut, detail, selaras dengan strategi penyelesaian masalah di berbagai area/bidang kerja',
                    'text_report' => 'Asesi telah mampu merumuskan langkah-langkah eksekusi atau program utama secara runtut, detail, selaras dengan strategi penyelesaian masalah di berbagai area/bidang kerja. Langkah-langkah yang dirumuskan sudah dapat diimplementasikan dengan jelas.'
                ]
            ],
            // Aspek 5: Mitigasi Risiko
            5 => [
                0 => [
                    'deskripsi_level' => 'Belum membuat mitigasi risiko',
                    'text_report' => 'Asesi belum membuat mitigasi risiko'
                ],
                1 => [
                    'deskripsi_level' => 'Asesi telah mampu memberikan mitigasi risiko secara sederhana',
                    'text_report' => 'Asesi telah mampu memberikan mitigasi risiko secara sederhana. Namun, mitigasi risiko belum mencakup semua program utama yang diusulkan.'
                ],
                2 => [
                    'deskripsi_level' => 'Asesi telah mampu memberikan mitigasi risiko pada setiap program utama',
                    'text_report' => 'Asesi telah mampu memberikan mitigasi risiko pada setiap program utama. Namun, mitigasi risiko dapat lebih lengkap dan detail dijabarkan untuk setiap program.'
                ],
                3 => [
                    'deskripsi_level' => 'Asesi telah mampu memberikan mitigasi risiko pada setiap program utama secara lengkap dan detail',
                    'text_report' => 'Asesi telah mampu memberikan mitigasi risiko pada setiap program utama secara lengkap dan detail. Mitigasi risiko yang diberikan sudah mencakup identifikasi risiko, dampak risiko, dan langkah-langkah mitigasi yang jelas.'
                ]
            ],
            // Aspek 6: Pemanfaatan Informasi Keuangan
            6 => [
                0 => [
                    'deskripsi_level' => 'Belum memanfaatkan informasi keuangan',
                    'text_report' => 'Asesi belum memanfaatkan informasi keuangan'
                ],
                1 => [
                    'deskripsi_level' => 'Asesi telah menggunakan informasi keuangan secara umum',
                    'text_report' => 'Asesi telah menggunakan informasi keuangan secara umum. Namun, informasi keuangan belum diinterpretasikan dan diolah lebih lanjut dalam merumuskan strategi penyelesaian masalah.'
                ],
                2 => [
                    'deskripsi_level' => 'Asesi telah menggunakan informasi keuangan secara umum dan memberikan intepretasi pada informasi tersebut',
                    'text_report' => 'Asesi telah menggunakan informasi keuangan secara umum dan memberikan interpretasi pada informasi tersebut. Namun, informasi keuangan dapat lebih diolah dan diintegrasikan dalam merumuskan strategi penyelesaian masalah.'
                ],
                3 => [
                    'deskripsi_level' => 'Asesi telah menggunakan, memberikan intepretasi, dan mengolah informasi keuangan dalam merumuskan strategi penyelesaian masalah',
                    'text_report' => 'Asesi telah menggunakan, memberikan interpretasi, dan mengolah informasi keuangan dalam merumuskan strategi penyelesaian masalah. Informasi keuangan telah terintegrasi dengan baik dalam analisis dan strategi yang diusulkan.'
                ]
            ]
        ];

        // Loop untuk setiap kategori (PQ dan BQ)
        foreach ([$kategoriPQ, $kategoriBQ] as $kategori) {
            $aspekList = AspekPenilaianStudiKasus::where('kategori_studi_kasus_id', $kategori->id)
                ->orderBy('nomor')
                ->get();

            foreach ($aspekList as $aspek) {
                if (isset($levelData[$aspek->nomor])) {
                    foreach ($levelData[$aspek->nomor] as $level => $data) {
                        LevelPenilaianStudiKasus::updateOrCreate(
                            [
                                'aspek_penilaian_studi_kasus_id' => $aspek->id,
                                'level' => $level
                            ],
                            [
                                'deskripsi_level' => $data['deskripsi_level'],
                                'text_report' => $data['text_report']
                            ]
                        );
                    }
                }
            }
        }
    }
}
