@extends('peserta.layouts.app')

@section('title', $penilaian->nama)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $penilaian->nama }}</h1>
            <p class="text-gray-600 mt-2">{{ $penilaian->jenis_text }}</p>
        </div>

        <div class="space-y-8">
            <!-- Petunjuk Pengisian -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Petunjuk Pengisian:</h2>
                <div class="prose max-w-none">
                    @if(isset($sesiAssessment) && !empty(trim($sesiAssessment->instruksi_khusus)))
                        {!! $sesiAssessment->instruksi_khusus !!}
                    @elseif(!empty($penilaian->petunjuk))
                        {!! $penilaian->petunjuk !!}
                    @else
                        <p class="text-gray-500 italic">Petunjuk pengisian belum tersedia.</p>
                    @endif
                </div>
            </div>

            <!-- Konten Assessment -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                @if($penilaian->jenis === 'studi_kasus')
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Deskripsi Soal</h2>
                    <div class="bg-white border-2 border-blue-200 rounded-lg p-6 min-h-32 mb-6">
                        <div class="text-gray-700 leading-relaxed">
                            {{ $penilaian->konten ?? 'Deskripsi soal studi kasus akan ditampilkan di sini.' }}
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Jawaban Anda</h3>
                    <textarea rows="12" class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Tuliskan jawaban Anda di sini..."></textarea>
                @elseif($penilaian->jenis === 'in_tray')
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Instruksi In-Tray</h2>
                    <textarea rows="10" class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Tuliskan jawaban Anda di sini..."></textarea>
                @elseif($penilaian->jenis === 'roleplay' || $penilaian->jenis === 'role_play')
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Instruksi Role-Play</h2>
                    <textarea rows="10" class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Tuliskan catatan Anda di sini..."></textarea>
                @elseif($penilaian->jenis === 'fgd')
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Instruksi FGD</h2>
                    <textarea rows="10" class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Tuliskan catatan FGD Anda di sini..."></textarea>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <button type="button" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Simpan Sementara
                </button>
                <button type="button" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Simpan Final
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
