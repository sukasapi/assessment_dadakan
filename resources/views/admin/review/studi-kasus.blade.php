@extends('admin.layouts.app')

@section('title', 'Review Jawaban Studi Kasus')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Review Jawaban Studi Kasus</h1>
                <p class="text-sm text-gray-600">{{ $penilaian->nama }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.progress.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Kembali ke Progress
                </a>
            </div>
        </div>

        <!-- Assessment Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Informasi Assessment</h3>
                    <p class="text-sm text-gray-600">{{ $penilaian->nama }}</p>
                    <p class="text-sm text-gray-500">{{ $penilaian->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Jenis</h3>
                    <p class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $penilaian->jenis_penilaian) }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Total Jawaban</h3>
                    <p class="text-2xl font-semibold text-blue-600">{{ $jawabanList->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Jawaban List -->
        @if($jawabanList->count() > 0)
        <div class="space-y-6">
            @foreach($jawabanList as $jawaban)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $jawaban->peserta->nama_lengkap }}</h3>
                            <p class="text-sm text-gray-500">{{ $jawaban->peserta->email }}</p>
                            <p class="text-sm text-gray-500">{{ $jawaban->peserta->jabatan_saat_ini }} - {{ $jawaban->peserta->instansi }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Submit: {{ \Carbon\Carbon::parse($jawaban->waktu_simpan)->format('d/m/Y H:i') }}</p>
                            @if($jawaban->status)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($jawaban->status) }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Case Study Content -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">Studi Kasus:</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $jawaban->konten_studi_kasus ?: 'Tidak ada konten studi kasus' }}</p>
                        </div>
                    </div>

                    <!-- Participant's Answer -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">Jawaban Peserta:</h4>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $jawaban->jawaban_peserta ?: 'Belum ada jawaban' }}</p>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    @if($jawaban->catatan_tambahan)
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">Catatan Tambahan:</h4>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $jawaban->catatan_tambahan }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Assessment Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Waktu Mulai:</h4>
                            <p class="text-sm text-gray-600">
                                {{ $jawaban->waktu_mulai ? \Carbon\Carbon::parse($jawaban->waktu_mulai)->format('d/m/Y H:i:s') : 'Belum dimulai' }}
                            </p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Waktu Selesai:</h4>
                            <p class="text-sm text-gray-600">
                                {{ $jawaban->waktu_selesai ? \Carbon\Carbon::parse($jawaban->waktu_selesai)->format('d/m/Y H:i:s') : 'Belum selesai' }}
                            </p>
                        </div>
                        @if($jawaban->waktu_mulai && $jawaban->waktu_selesai)
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Durasi:</h4>
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($jawaban->waktu_mulai)->diffInMinutes($jawaban->waktu_selesai) }} menit
                            </p>
                        </div>
                        @endif
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Status:</h4>
                            <p class="text-sm text-gray-600">
                                {{ $jawaban->status ? ucfirst($jawaban->status) : 'Belum ada status' }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button 
                            onclick="updateStatus({{ $jawaban->id }}, 'sedang_berlangsung')"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600"
                        >
                            Set Sedang Berlangsung
                        </button>
                        <button 
                            onclick="updateStatus({{ $jawaban->id }}, 'selesai')"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600"
                        >
                            Set Selesai
                        </button>
                        <button 
                            onclick="updateStatus({{ $jawaban->id }}, 'belum_mulai')"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600"
                        >
                            Reset
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada jawaban</h3>
            <p class="mt-1 text-sm text-gray-500">Jawaban studi kasus akan muncul di sini setelah peserta submit</p>
        </div>
        @endif
    </div>
</div>

<script>
function updateStatus(jawabanId, newStatus) {
    if (!confirm('Apakah Anda yakin ingin mengubah status menjadi "' + newStatus + '"?')) {
        return;
    }
    
    // Update status via AJAX
    fetch('{{ route("admin.progress.update-status", ":kemajuanId") }}'.replace(':kemajuanId', jawabanId), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengupdate status: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan saat mengupdate status');
    });
}
</script>
@endsection
