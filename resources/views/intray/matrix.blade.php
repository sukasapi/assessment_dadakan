@extends('peserta.layouts.app')

@section('title', 'Matriks Prioritas In-Tray')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Matriks Prioritas In-Tray</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Sesi: <span class="font-medium">{{ $sesi->nama }}</span> | 
                        Peserta: <span class="font-medium">{{ $peserta->nama_lengkap }}</span>
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    @if($isAdmin)
                        <a href="{{ route('admin.progress.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Progress
                        </a>
                    @else
                        <a href="{{ route('peserta.assessment.kerja', $inTrayAssessment->penilaian_id) }}?sesi={{ $sesi->id }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Assessment
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Petunjuk Penggunaan Matriks Prioritas:</h3>
            <ol class="text-sm text-blue-700 space-y-1">
                <li><strong>1.</strong> Pahami peran dan tanggung jawab dari jabatan yang Anda perankan dan ekspektasinya</li>
                <li><strong>2.</strong> Perhatikan detail tanggal-tanggal/waktu-waktu penting</li>
                <li><strong>3.</strong> Buat skala prioritas berdasarkan matriks di bawah ini</li>
            </ol>
        </div>

        <!-- Priority Matrix -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Quadrant 1: Mendesak & Penting -->
                    <div class="border-2 border-red-300 rounded-lg p-4 bg-red-50">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-red-800">1. Mendesak & Penting</h3>
                            <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded">{{ count($matrix['mendesak_penting']) }} memo</span>
                        </div>
                        <p class="text-sm text-red-700 mb-3 font-medium">Lakukan/putuskan segera</p>
                        <div class="space-y-2">
                            @forelse($matrix['mendesak_penting'] as $memo)
                                <div class="bg-white border border-red-200 rounded p-3">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 text-sm">{{ $memo['judul'] }}</h4>
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit(strip_tags($memo['konten']), 100) }}</p>
                                            @if($memo['disposisi'])
                                                <p class="text-xs text-blue-600 mt-1"><strong>Disposisi:</strong> {{ $memo['disposisi'] }}</p>
                                            @endif
                                        </div>
                                        <button class="ml-2 text-blue-600 hover:text-blue-800 text-xs view-memo-btn" 
                                                data-id="{{ $memo['id'] }}"
                                                data-judul="{{ $memo['judul'] }}"
                                                data-konten="{{ $memo['konten'] }}"
                                                data-disposisi="{{ $memo['disposisi'] }}"
>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500 text-sm">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Tidak ada memo
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Quadrant 3: Mendesak & Tidak Penting -->
                    <div class="border-2 border-blue-300 rounded-lg p-4 bg-blue-50">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-blue-800">3. Mendesak & Tidak Penting</h3>
                            <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">{{ count($matrix['mendesak_tidak_penting']) }} memo</span>
                        </div>
                        <p class="text-sm text-blue-700 mb-3 font-medium">Bisa penting/mendesak untuk orang lain. Delegasikan</p>
                        <div class="space-y-2">
                            @forelse($matrix['mendesak_tidak_penting'] as $memo)
                                <div class="bg-white border border-blue-200 rounded p-3">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 text-sm">{{ $memo['judul'] }}</h4>
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit(strip_tags($memo['konten']), 100) }}</p>
                                            @if($memo['disposisi'])
                                                <p class="text-xs text-blue-600 mt-1"><strong>Disposisi:</strong> {{ $memo['disposisi'] }}</p>
                                            @endif
                                        </div>
                                        <button class="ml-2 text-blue-600 hover:text-blue-800 text-xs view-memo-btn" 
                                                data-id="{{ $memo['id'] }}"
                                                data-judul="{{ $memo['judul'] }}"
                                                data-konten="{{ $memo['konten'] }}"
                                                data-disposisi="{{ $memo['disposisi'] }}"
>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500 text-sm">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Tidak ada memo
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Quadrant 2: Tidak Mendesak & Penting -->
                    <div class="border-2 border-yellow-300 rounded-lg p-4 bg-yellow-50">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-yellow-800">2. Tidak Mendesak & Penting</h3>
                            <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded">{{ count($matrix['tidak_mendesak_penting']) }} memo</span>
                        </div>
                        <p class="text-sm text-yellow-700 mb-3 font-medium">Lakukan/putuskan ketika anda benar-benar dalam kondisi siap</p>
                        <div class="space-y-2">
                            @forelse($matrix['tidak_mendesak_penting'] as $memo)
                                <div class="bg-white border border-yellow-200 rounded p-3">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 text-sm">{{ $memo['judul'] }}</h4>
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit(strip_tags($memo['konten']), 100) }}</p>
                                            @if($memo['disposisi'])
                                                <p class="text-xs text-blue-600 mt-1"><strong>Disposisi:</strong> {{ $memo['disposisi'] }}</p>
                                            @endif
                                        </div>
                                        <button class="ml-2 text-blue-600 hover:text-blue-800 text-xs view-memo-btn" 
                                                data-id="{{ $memo['id'] }}"
                                                data-judul="{{ $memo['judul'] }}"
                                                data-konten="{{ $memo['konten'] }}"
                                                data-disposisi="{{ $memo['disposisi'] }}"
>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500 text-sm">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Tidak ada memo
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Quadrant 4: Tidak Mendesak & Tidak Penting -->
                    <div class="border-2 border-green-300 rounded-lg p-4 bg-green-50">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-green-800">4. Tidak Mendesak & Tidak Penting</h3>
                            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">{{ count($matrix['tidak_mendesak_tidak_penting']) }} memo</span>
                        </div>
                        <p class="text-sm text-green-700 mb-3 font-medium">Abaikan/kerjakan jika senggang</p>
                        <div class="space-y-2">
                            @forelse($matrix['tidak_mendesak_tidak_penting'] as $memo)
                                <div class="bg-white border border-green-200 rounded p-3">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 text-sm">{{ $memo['judul'] }}</h4>
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit(strip_tags($memo['konten']), 100) }}</p>
                                            @if($memo['disposisi'])
                                                <p class="text-xs text-blue-600 mt-1"><strong>Disposisi:</strong> {{ $memo['disposisi'] }}</p>
                                            @endif
                                        </div>
                                        <button class="ml-2 text-blue-600 hover:text-blue-800 text-xs view-memo-btn" 
                                                data-id="{{ $memo['id'] }}"
                                                data-judul="{{ $memo['judul'] }}"
                                                data-konten="{{ $memo['konten'] }}"
                                                data-disposisi="{{ $memo['disposisi'] }}"
>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500 text-sm">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Tidak ada memo
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Memo Detail Modal -->
<div id="memoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Detail Memo</h3>
                <button onclick="closeMemoModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Konten Memo</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md text-sm text-gray-900" id="modalContent"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Disposisi</label>
                    <div class="mt-1 p-3 bg-blue-50 rounded-md text-sm text-gray-900" id="modalDisposisi"></div>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button onclick="closeMemoModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Event listener for memo detail buttons
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('view-memo-btn')) {
        const id = e.target.getAttribute('data-id');
        const judul = e.target.getAttribute('data-judul');
        const konten = e.target.getAttribute('data-konten');
        const disposisi = e.target.getAttribute('data-disposisi');
        showMemoDetail(id, judul, konten, disposisi);
    }
});

function showMemoDetail(id, judul, konten, disposisi) {
    document.getElementById('modalTitle').textContent = judul;
    // Remove HTML tags from content
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = konten;
    document.getElementById('modalContent').textContent = tempDiv.textContent || tempDiv.innerText || '';
    document.getElementById('modalDisposisi').textContent = disposisi || 'Tidak ada disposisi';
    
    document.getElementById('memoModal').classList.remove('hidden');
}

function closeMemoModal() {
    document.getElementById('memoModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('memoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMemoModal();
    }
});
</script>
@endsection
