@extends('peserta.layouts.app')

@section('title', 'Matriks Prioritas In-Tray')

@section('content')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.memo-content-container {
    word-wrap: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
    max-width: 100%;
    overflow: hidden;
}

.memo-content-container * {
    max-width: 100% !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
}
</style>
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
       <!-- <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Petunjuk Penggunaan Matriks Prioritas:</h3>
            <ol class="text-sm text-blue-700 space-y-1">
                <li><strong>1.</strong> Pahami peran dan tanggung jawab dari jabatan yang Anda perankan dan ekspektasinya</li>
                <li><strong>2.</strong> Perhatikan detail tanggal-tanggal/waktu-waktu penting</li>
                <li><strong>3.</strong> Buat skala prioritas berdasarkan matriks di bawah ini</li>
            </ol>
        </div>-->

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
                                <div class="bg-white border border-red-200 rounded p-3 overflow-hidden">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0 memo-content-container">
                                            <h4 class="font-medium text-gray-900 text-sm break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;">{{ $memo['judul'] }}</h4>
                                            <p class="text-xs text-gray-600 mt-1 break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;">{{ Str::limit(strip_tags($memo['konten']), 100) }}</p>
                                            @if($memo['disposisi'])
                                                <p class="text-xs text-blue-600 mt-1 break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;"><strong>Disposisi:</strong> {{ $memo['disposisi'] }}</p>
                                            @endif
                                        </div>
                                        <button class="ml-2 text-blue-600 hover:text-blue-800 text-xs view-memo-btn" 
                                                data-id="{{ $memo['id'] }}"
                                                data-judul="{{ $memo['judul'] }}"
                                                data-konten="{{ $memo['konten'] }}"
                                                data-disposisi="{{ $memo['disposisi'] }}"
                                                title="Lihat Detail Memo">
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
                                <div class="bg-white border border-blue-200 rounded p-3 overflow-hidden">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0 memo-content-container">
                                            <h4 class="font-medium text-gray-900 text-sm break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;">{{ $memo['judul'] }}</h4>
                                            <p class="text-xs text-gray-600 mt-1 break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;">{{ Str::limit(strip_tags($memo['konten']), 100) }}</p>
                                            @if($memo['disposisi'])
                                                <p class="text-xs text-blue-600 mt-1 break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;"><strong>Disposisi:</strong> {{ $memo['disposisi'] }}</p>
                                            @endif
                                        </div>
                                        <button class="ml-2 text-blue-600 hover:text-blue-800 text-xs view-memo-btn" 
                                                data-id="{{ $memo['id'] }}"
                                                data-judul="{{ $memo['judul'] }}"
                                                data-konten="{{ $memo['konten'] }}"
                                                data-disposisi="{{ $memo['disposisi'] }}"
                                                title="Lihat Detail Memo">
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
                                <div class="bg-white border border-yellow-200 rounded p-3 overflow-hidden">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0 memo-content-container">
                                            <h4 class="font-medium text-gray-900 text-sm break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;">{{ $memo['judul'] }}</h4>
                                            <p class="text-xs text-gray-600 mt-1 break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;">{{ Str::limit(strip_tags($memo['konten']), 100) }}</p>
                                            @if($memo['disposisi'])
                                                <p class="text-xs text-blue-600 mt-1 break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;"><strong>Disposisi:</strong> {{ $memo['disposisi'] }}</p>
                                            @endif
                                        </div>
                                        <button class="ml-2 text-blue-600 hover:text-blue-800 text-xs view-memo-btn" 
                                                data-id="{{ $memo['id'] }}"
                                                data-judul="{{ $memo['judul'] }}"
                                                data-konten="{{ $memo['konten'] }}"
                                                data-disposisi="{{ $memo['disposisi'] }}"
                                                title="Lihat Detail Memo">
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
                                <div class="bg-white border border-green-200 rounded p-3 overflow-hidden">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0 memo-content-container">
                                            <h4 class="font-medium text-gray-900 text-sm break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;">{{ $memo['judul'] }}</h4>
                                            <p class="text-xs text-gray-600 mt-1 break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;">{{ Str::limit(strip_tags($memo['konten']), 100) }}</p>
                                            @if($memo['disposisi'])
                                                <p class="text-xs text-blue-600 mt-1 break-words overflow-hidden" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;"><strong>Disposisi:</strong> {{ $memo['disposisi'] }}</p>
                                            @endif
                                        </div>
                                        <button class="ml-2 text-blue-600 hover:text-blue-800 text-xs view-memo-btn" 
                                                data-id="{{ $memo['id'] }}"
                                                data-judul="{{ $memo['judul'] }}"
                                                data-konten="{{ $memo['konten'] }}"
                                                data-disposisi="{{ $memo['disposisi'] }}"
                                                title="Lihat Detail Memo">
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
<div id="memoModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    <div class="relative w-full h-full bg-white flex flex-col">
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <h3 class="text-base md:text-lg font-semibold" id="modalTitle">Detail Memo</h3>
            <div class="flex items-center gap-2">
                <button id="memoModalClose" class="px-3 py-1.5 text-sm border rounded hover:bg-gray-50">Tutup</button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-4 md:p-6">
            <div id="memoModalContent" class="prose max-w-none mb-6"></div>
            <hr class="my-8 border-gray-200">
            
            <!-- Disposisi Section -->
            <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-4 md:p-5">
                    <label class="block text-sm font-medium text-gray-800 mb-2">Disposisi</label>
                    <textarea id="memoModalDisposisi" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="contoh: delegasi ke sekretaris, arsip, tindak lanjut, dll"></textarea>
                    <div class="mt-3 flex justify-end">
                        <button id="saveDisposisiBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <span id="saveDisposisiBtnText">Simpan Disposisi</span>
                            <span id="saveDisposisiBtnLoading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Store current memo ID for saving disposisi
let currentMemoId = null;
let currentPenilaianId = null;
let currentSesiId = null;

// Event listener for memo detail buttons
document.addEventListener('click', function(e) {
    // Check if clicked element is the button or its child (SVG)
    const button = e.target.closest('.view-memo-btn');
    if (button) {
        const id = button.getAttribute('data-id');
        const judul = button.getAttribute('data-judul');
        const konten = button.getAttribute('data-konten');
        const disposisi = button.getAttribute('data-disposisi');
        showMemoDetail(id, judul, konten, disposisi);
    }
});

function showMemoDetail(id, judul, konten, disposisi) {
    // Store current memo ID and other needed IDs
    currentMemoId = id;
    currentPenilaianId = {{ $inTrayAssessment->penilaian_id }};
    currentSesiId = {{ $sesi->id }};
    
    document.getElementById('modalTitle').textContent = judul;
    
    // Handle content - display formatted content instead of raw HTML
    const contentDiv = document.getElementById('memoModalContent');
    if (konten && konten.trim() !== '') {
        // Content is already decoded from controller, display it as formatted HTML
        contentDiv.innerHTML = konten;
        
        // Apply some basic styling to make it more readable
        contentDiv.style.fontFamily = 'system-ui, -apple-system, sans-serif';
        contentDiv.style.lineHeight = '1.6';
        contentDiv.style.color = '#374151';
        
        // Style paragraphs
        const paragraphs = contentDiv.querySelectorAll('p');
        paragraphs.forEach(p => {
            p.style.marginBottom = '1rem';
            p.style.fontSize = '0.95rem';
        });
        
        // Style lists
        const lists = contentDiv.querySelectorAll('ul, ol');
        lists.forEach(list => {
            list.style.marginLeft = '1.5rem';
            list.style.marginBottom = '1rem';
        });
        
        // Style list items
        const listItems = contentDiv.querySelectorAll('li');
        listItems.forEach(li => {
            li.style.marginBottom = '0.5rem';
        });
        
        // Style strong/bold text
        const strongElements = contentDiv.querySelectorAll('strong, b');
        strongElements.forEach(strong => {
            strong.style.fontWeight = '600';
            strong.style.color = '#1f2937';
        });
        
    } else {
        contentDiv.innerHTML = '<p class="text-gray-500 italic">Tidak ada konten memo</p>';
    }
    
    // Handle disposisi - set textarea value
    const disposisiTextarea = document.getElementById('memoModalDisposisi');
    if (disposisi && disposisi.trim() !== '') {
        // Create a temporary div to process the content
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = disposisi;
        
        // Convert to plain text but preserve line breaks
        const textContent = tempDiv.textContent || tempDiv.innerText || '';
        disposisiTextarea.value = textContent;
    } else {
        disposisiTextarea.value = '';
    }
    
    // Show modal
    const modal = document.getElementById('memoModal');
    modal.classList.remove('hidden');
}

function closeMemoModal() {
    const modal = document.getElementById('memoModal');
    modal.classList.add('hidden');
    
    // Clear content to prevent showing old data
    document.getElementById('modalTitle').textContent = 'Detail Memo';
    document.getElementById('memoModalContent').innerHTML = '';
    document.getElementById('memoModalDisposisi').value = '';
    
    // Reset current memo ID
    currentMemoId = null;
    currentPenilaianId = null;
    currentSesiId = null;
}

// Close modal when clicking close button
document.getElementById('memoModalClose').addEventListener('click', function() {
    closeMemoModal();
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('memoModal');
        if (!modal.classList.contains('hidden')) {
            closeMemoModal();
        }
    }
});

// Save disposisi function
async function saveDisposisi() {
    if (!currentMemoId || !currentPenilaianId || !currentSesiId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Data memo tidak lengkap. Silakan tutup dan buka kembali modal.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    const disposisiTextarea = document.getElementById('memoModalDisposisi');
    const disposisiValue = disposisiTextarea ? disposisiTextarea.value.trim() : '';
    
    const saveBtn = document.getElementById('saveDisposisiBtn');
    const saveBtnText = document.getElementById('saveDisposisiBtnText');
    const saveBtnLoading = document.getElementById('saveDisposisiBtnLoading');
    
    // Show loading state
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
    if (saveBtnText) saveBtnText.classList.add('hidden');
    if (saveBtnLoading) saveBtnLoading.classList.remove('hidden');

    try {
        const response = await fetch(`/penilaian/in-tray/${currentPenilaianId}/update-disposisi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                latihan_in_tray_id: parseInt(currentMemoId),
                disposisi: disposisiValue,
                sesi_penilaian_id: parseInt(currentSesiId)
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || data.message || 'Gagal menyimpan disposisi');
        }

        // Show success notification
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: data.message || 'Disposisi berhasil disimpan',
            confirmButtonText: 'OK',
            confirmButtonColor: '#10b981',
            timer: 2000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });

        // Update the button data attribute to reflect saved state
        const viewButtons = document.querySelectorAll(`.view-memo-btn[data-id="${currentMemoId}"]`);
        viewButtons.forEach(btn => {
            btn.setAttribute('data-disposisi', disposisiValue);
        });

    } catch (error) {
        console.error('Error saving disposisi:', error);
        
        // Show error notification
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menyimpan',
            text: error.message || 'Terjadi kesalahan saat menyimpan disposisi. Silakan coba lagi.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#ef4444',
            width: '500px'
        });
    } finally {
        // Hide loading state
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
        if (saveBtnText) saveBtnText.classList.remove('hidden');
        if (saveBtnLoading) saveBtnLoading.classList.add('hidden');
    }
}

// Add event listener for save disposisi button
document.addEventListener('DOMContentLoaded', function() {
    const saveDisposisiBtn = document.getElementById('saveDisposisiBtn');
    if (saveDisposisiBtn) {
        saveDisposisiBtn.addEventListener('click', saveDisposisi);
    }
});
</script>
@endsection
