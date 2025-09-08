@extends('peserta.layouts.app')

@section('title', 'Petunjuk Penggunaan (Peserta)')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Petunjuk Penggunaan - Peserta</h1>

    <div class="space-y-6">
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold mb-3">Masuk dan Dashboard</h2>
            <ol class="list-decimal ml-6 text-gray-700 leading-7">
                <li>Login menggunakan PIN alfanumerik (6–10 karakter).</li>
                <li>Pada dashboard, pilih sesi aktif yang terdaftar untuk Anda.</li>
                <li>Jika sesi belum aktif, tombol assessment akan nonaktif.</li>
            </ol>
        </section>

        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold mb-3">Pengerjaan Assessment</h2>
            <ol class="list-decimal ml-6 text-gray-700 leading-7">
                <li>Studi Kasus: baca PDF deskripsi soal yang tampil di halaman, tulis jawaban, lalu Simpan Sementara atau Simpan Final.</li>
                <li>In‑Tray: urutkan kartu memo dari prioritas tertinggi ke terendah, isi disposisi bila perlu, lalu simpan.</li>
                <li>Roleplay & FGD: tulis catatan pada kolom yang tersedia, simpan sementara atau final.</li>
                <li>Jika durasi ditetapkan, perhatikan penghitung waktu di halaman.</li>
            </ol>
        </section>

        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold mb-3">Penyimpanan</h2>
            <ul class="list-disc ml-6 text-gray-700 leading-7">
                <li>Simpan Sementara: pekerjaan tersimpan sebagai draft, dapat dilanjutkan.</li>
                <li>Simpan Final: menandai selesai dan mengunci pengerjaan assessment tersebut.</li>
            </ul>
        </section>
    </div>
</div>
@endsection


