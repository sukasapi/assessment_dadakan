@extends('admin.layouts.app')

@section('title', 'Progress Peserta')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Progress Assessment Peserta</h1>
                <p class="text-sm text-gray-600">Detail progress assessment untuk {{ $peserta->nama_lengkap }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.progress.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Kembali ke Progress
                </a>
                <a href="{{ route('admin.peserta.show', $peserta->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Detail Peserta
                </a>
            </div>
        </div>

        <!-- Peserta Info Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-xl font-medium text-blue-600">{{ substr($peserta->nama_lengkap, 0, 2) }}</span>
                    </div>
                </div>
                <div class="ml-6">
                    <h3 class="text-xl font-medium text-gray-900">{{ $peserta->nama_lengkap }}</h3>
                    <p class="text-sm text-gray-500">{{ $peserta->email }}</p>
                    <p class="text-sm text-gray-500">{{ $peserta->jabatan_saat_ini }} - {{ $peserta->instansi }}</p>
                    <p class="text-sm text-gray-500">Grade: {{ $peserta->grade }}</p>
                </div>
                <div class="ml-auto">
                    @if($peserta->aktif)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Nonaktif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Progress Summary -->
        @if($progressList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php
                $totalProgress = $progressList->flatten()->count();
                $completedProgress = $progressList->flatten()->where('status', 'selesai')->count();
                $ongoingProgress = $progressList->flatten()->where('status', 'sedang_berlangsung')->count();
                $notStartedProgress = $progressList->flatten()->where('status', 'belum_mulai')->count();
            @endphp
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Assessment</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalProgress }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Selesai</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $completedProgress }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Sedang Berlangsung</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $ongoingProgress }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Belum Mulai</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $notStartedProgress }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Details by Type -->
        <div class="space-y-6">
            @foreach($progressList as $jenis => $items)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $jenis) }}</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($items as $progress)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $progress->penilaian->nama }}</h4>
                                <p class="text-sm text-gray-500">{{ $progress->penilaian->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                                @if($progress->waktu_mulai)
                                <p class="text-xs text-gray-400 mt-1">
                                    Mulai: {{ \Carbon\Carbon::parse($progress->waktu_mulai)->format('d/m/Y H:i') }}
                                </p>
                                @endif
                                @if($progress->waktu_selesai)
                                <p class="text-xs text-gray-400 mt-1">
                                    Selesai: {{ \Carbon\Carbon::parse($progress->waktu_selesai)->format('d/m/Y H:i') }}
                                </p>
                                @endif
                            </div>
                            <div class="flex items-center space-x-4">
                                @php
                                    $statusColors = [
                                        'belum_mulai' => 'bg-gray-100 text-gray-800',
                                        'sedang_berlangsung' => 'bg-yellow-100 text-yellow-800',
                                        'selesai' => 'bg-green-100 text-green-800'
                                    ];
                                    $statusColor = $statusColors[$progress->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $progress->status)) }}
                                </span>
                                
                                <!-- Status Update Buttons -->
                                <div class="flex space-x-2">
                                    @if($progress->status !== 'belum_mulai')
                                    <button 
                                        onclick="updateStatus({{ $progress->id }}, 'belum_mulai')"
                                        class="px-3 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600"
                                    >
                                        Reset
                                    </button>
                                    @endif
                                    
                                    @if($progress->status !== 'sedang_berlangsung')
                                    <button 
                                        onclick="updateStatus({{ $progress->id }}, 'sedang_berlangsung')"
                                        class="px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600"
                                    >
                                        Mulai
                                    </button>
                                    @endif
                                    
                                    @if($progress->status !== 'selesai')
                                    <button 
                                        onclick="updateStatus({{ $progress->id }}, 'selesai')"
                                        class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600"
                                    >
                                        Selesai
                                    </button>
                                    @endif
                                </div>
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
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada progress assessment</h3>
            <p class="mt-1 text-sm text-gray-500">Progress assessment akan muncul di sini setelah peserta mulai mengerjakan</p>
        </div>
        @endif
    </div>
</div>

<script>
function updateStatus(kemajuanId, newStatus) {
    if (!confirm('Apakah Anda yakin ingin mengubah status menjadi "' + newStatus + '"?')) {
        return;
    }
    
    fetch('{{ route("admin.progress.update-status", ":kemajuanId") }}'.replace(':kemajuanId', kemajuanId), {
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
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate status');
    });
}
</script>
@endsection
