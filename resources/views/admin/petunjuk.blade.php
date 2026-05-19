@extends('admin.layouts.app')

@section('title', 'Petunjuk Penggunaan (Admin)')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Petunjuk Penggunaan - Admin',
    'subtitle' => 'Panduan penggunaan fitur admin Assessment Center',
])

<div class="max-w-5xl">

    <div class="space-y-6">
        <section class="admin-card-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold mb-3">Manajemen Sesi</h2>
            <ol class="list-decimal ml-6 text-primary leading-7">
                <li>Buka menu Sesi, klik "Buat Sesi" untuk menambahkan sesi baru.</li>
                <li>Pilih assessment yang akan dijalankan (Studi Kasus/In‑Tray/Roleplay/FGD), atur urutan dan durasi.</li>
                <li>Untuk Studi Kasus, unggah file PDF deskripsi soal. Untuk In‑Tray, isi memo lebih dari satu bila diperlukan.</li>
                <li>Simpan. Status sesi dapat diubah dari daftar sesi via dropdown.</li>
            </ol>
        </section>

        <section class="admin-card-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold mb-3">Manajemen Peserta</h2>
            <ol class="list-decimal ml-6 text-primary leading-7">
                <li>Tambah peserta secara manual atau gunakan impor CSV dari menu Peserta.</li>
                <li>Pastikan format header CSV sesuai template. Sistem otomatis membersihkan BOM.</li>
                <li>Daftarkan peserta ke sesi melalui halaman detail sesi.</li>
            </ol>
        </section>

        <section class="admin-card-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold mb-3">Peninjauan Jawaban</h2>
            <ol class="list-decimal ml-6 text-primary leading-7">
                <li>Buka menu Review untuk melihat jawaban per jenis assessment.</li>
                <li>Gunakan ekspor bila perlu mengunduh ringkasan.</li>
            </ol>
        </section>
    </div>
</div>
@endsection


