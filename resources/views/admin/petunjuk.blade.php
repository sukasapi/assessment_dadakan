@extends('admin.layouts.app')

@section('title', 'Petunjuk Penggunaan (Admin)')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Petunjuk Penggunaan - Admin</h1>

    <div class="space-y-6">
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold mb-3">Manajemen Sesi</h2>
            <ol class="list-decimal ml-6 text-gray-700 leading-7">
                <li>Buka menu Sesi, klik "Buat Sesi" untuk menambahkan sesi baru.</li>
                <li>Pilih assessment yang akan dijalankan (Studi Kasus/In‑Tray/Roleplay/FGD), atur urutan dan durasi.</li>
                <li>Untuk Studi Kasus, unggah file PDF deskripsi soal. Untuk In‑Tray, isi memo lebih dari satu bila diperlukan.</li>
                <li>Simpan. Status sesi dapat diubah dari daftar sesi via dropdown.</li>
            </ol>
        </section>

        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold mb-3">Manajemen Peserta</h2>
            <ol class="list-decimal ml-6 text-gray-700 leading-7">
                <li>Tambah peserta secara manual atau gunakan impor CSV dari menu Peserta.</li>
                <li>Pastikan format header CSV sesuai template. Sistem otomatis membersihkan BOM.</li>
                <li>Daftarkan peserta ke sesi melalui halaman detail sesi.</li>
            </ol>
        </section>

        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold mb-3">Peninjauan Jawaban</h2>
            <ol class="list-decimal ml-6 text-gray-700 leading-7">
                <li>Buka menu Review untuk melihat jawaban per jenis assessment.</li>
                <li>Gunakan ekspor bila perlu mengunduh ringkasan.</li>
            </ol>
        </section>
    </div>
</div>
@endsection


