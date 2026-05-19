@extends('admin.layouts.app')

@section('title', 'Review Jawaban In-Tray')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Review Jawaban In-Tray',
    'subtitle' => $penilaian->nama,
    'actions' => '<a href="' . route('admin.progress.index') . '" class="admin-btn-secondary">Kembali ke Progress</a>',
])

        <!-- Assessment Info -->
        <div class="admin-card p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-primary">Informasi Assessment</h3>
                    <p class="text-sm text-tertiary">{{ $penilaian->nama }}</p>
                    <p class="text-sm text-tertiary">{{ $penilaian->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-primary">Jenis</h3>
                    <p class="text-sm text-tertiary capitalize">{{ str_replace('_', ' ', $penilaian->jenis_penilaian) }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-primary">Total Peserta</h3>
                    <p class="text-2xl font-semibold text-blue-600">{{ $jawabanList->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Jawaban List by Participant -->
        @if($jawabanList->count() > 0)
        <div class="space-y-6">
            @foreach($jawabanList as $pesertaId => $jawabanGroup)
            @php
                $peserta = $jawabanGroup->first()->peserta;
                $totalItems = $jawabanGroup->count();
                $completedItems = $jawabanGroup->where('status', 'selesai')->count();
            @endphp
            
            <div class="admin-card">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-primary">{{ $peserta->nama_lengkap }}</h3>
                            <p class="text-sm text-tertiary">{{ $peserta->email }}</p>
                            <p class="text-sm text-tertiary">{{ $peserta->jabatan_saat_ini }} - {{ $peserta->instansi }}</p>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <p class="text-sm text-tertiary">Progress</p>
                                    <p class="text-lg font-semibold text-blue-600">{{ $completedItems }}/{{ $totalItems }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm text-tertiary">Status</p>
                                    @if($completedItems == $totalItems)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    @elseif($completedItems > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Sedang Berlangsung
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Belum Mulai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($jawabanGroup as $jawaban)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-medium text-primary">Item {{ $loop->iteration }}</h4>
                                    @if($jawaban->latihanInTray)
                                    <p class="text-sm text-tertiary">{{ $jawaban->latihanInTray->judul ?: 'Tidak ada judul' }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-tertiary">
                                        Submit: {{ \Carbon\Carbon::parse($jawaban->waktu_simpan)->format('d/m/Y H:i') }}
                                    </p>
                                    @if($jawaban->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $jawaban->status === 'selesai' ? 'bg-green-100 text-green-800' : 
                                           ($jawaban->status === 'sedang_berlangsung' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($jawaban->status) }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <!-- In-Tray Content -->
                            @if($jawaban->latihanInTray)
                            <div class="mb-4">
                                <h5 class="font-medium text-primary mb-2">Konten In-Tray:</h5>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-sm text-primary">{{ $jawaban->latihanInTray->konten ?: 'Tidak ada konten' }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Participant's Disposition -->
                            <div class="mb-4">
                                <h5 class="font-medium text-primary mb-2">Disposisi Peserta:</h5>
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <p class="text-sm text-primary">{{ $jawaban->disposisi_peserta ?: 'Belum ada disposisi' }}</p>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            @if($jawaban->catatan_tambahan)
                            <div class="mb-4">
                                <h5 class="font-medium text-primary mb-2">Catatan Tambahan:</h5>
                                <div class="bg-yellow-50 rounded-lg p-3">
                                    <p class="text-sm text-primary">{{ $jawaban->catatan_tambahan }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Assessment Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-primary">Waktu Mulai:</span>
                                    <p class="text-tertiary">
                                        {{ $jawaban->waktu_mulai ? \Carbon\Carbon::parse($jawaban->waktu_mulai)->format('H:i:s') : '-' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="font-medium text-primary">Waktu Selesai:</span>
                                    <p class="text-tertiary">
                                        {{ $jawaban->waktu_selesai ? \Carbon\Carbon::parse($jawaban->waktu_selesai)->format('H:i:s') : '-' }}
                                    </p>
                                </div>
                                @if($jawaban->waktu_mulai && $jawaban->waktu_selesai)
                                <div>
                                    <span class="font-medium text-primary">Durasi:</span>
                                    <p class="text-tertiary">
                                        {{ \Carbon\Carbon::parse($jawaban->waktu_mulai)->diffInMinutes($jawaban->waktu_selesai) }} menit
                                    </p>
                                </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-2 mt-4 pt-3 border-t border-gray-200">
                                <button 
                                    onclick="updateStatus({{ $jawaban->id }}, 'sedang_berlangsung')"
                                    class="px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600"
                                >
                                    Mulai
                                </button>
                                <button 
                                    onclick="updateStatus({{ $jawaban->id }}, 'selesai')"
                                    class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600"
                                >
                                    Selesai
                                </button>
                                <button 
                                    onclick="updateStatus({{ $jawaban->id }}, 'belum_mulai')"
                                    class="px-3 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600"
                                >
                                    Reset
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-primary">Belum ada jawaban</h3>
            <p class="mt-1 text-sm text-tertiary">Jawaban in-tray akan muncul di sini setelah peserta submit</p>
        </div>
        @endif

<script>
function updateStatus(jawabanId, newStatus) {
    if (!confirm('Apakah Anda yakin ingin mengubah status menjadi "' + newStatus + '"?')) {
        return;
    }
    
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
