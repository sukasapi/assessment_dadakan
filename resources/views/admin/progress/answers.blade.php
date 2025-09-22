@extends('admin.layouts.app')

@section('title', 'Jawaban Peserta')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4 sm:mb-0">Jawaban Peserta Assessment</h1>
        <a href="{{ route('admin.progress.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700 transition-colors">
            ← Kembali ke Progress
        </a>
    </div>

    <!-- Filter Card -->
    <div class="mb-2 sm:mb-4">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-2 sm:p-4">
            <!-- Universal Search Bar -->
            <div class="mb-2 sm:mb-3">
            <div class="relative">
                <input type="text" 
                           id="universalSearch" 
                           placeholder="Cari berdasarkan nama sesi, nama peserta, instansi, dll..."
                           class="w-full px-3 py-2 pl-9 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            </div>
            
            <!-- Filter Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 mb-2 sm:mb-3">
                <!-- Left Column -->
                <div class="space-y-2 sm:space-y-3">
                    <!-- Filter Sesi -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Nama Sesi</label>
                        <select id="sessionFilter" class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-md text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Sesi</option>
                            @foreach(\App\Models\SesiPenilaian::orderBy('nama')->get() as $sesi)
                                <option value="{{ $sesi->nama }}" {{ request('session') == $sesi->nama ? 'selected' : '' }}>{{ $sesi->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Filter Jenis Assessment -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Jenis Assessment</label>
                        <select id="assessmentTypeFilter" class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-md text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Jenis</option>
                            <option value="studi_kasus">Studi Kasus</option>
                            <option value="in_tray">In-Tray (Semua Model)</option>
                            <option value="in_tray_urutan">In-Tray (Model Urutan)</option>
                            <option value="in_tray_prioritas">In-Tray (Model Prioritas)</option>
                            <option value="roleplay">Role-Play</option>
                            <option value="fgd">LGD/FGD</option>
                        </select>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-2 sm:space-y-3">
                    <!-- Filter Nama Peserta -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Nama Peserta</label>
                        <input type="text" 
                               id="participantNameFilter" 
                               placeholder="Masukkan nama peserta..."
                               class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-md text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Filter Instansi -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Instansi</label>
                        <input type="text" 
                               id="institutionFilter" 
                               placeholder="Masukkan nama instansi..."
                               class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-md text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-1 sm:gap-2">
                <button id="resetFilters" class="w-full sm:w-auto px-2 sm:px-3 py-1 sm:py-1.5 border border-gray-300 rounded-md text-xs sm:text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Reset
                </button>
                <button id="applyFilters" class="w-full sm:w-auto px-2 sm:px-3 py-1 sm:py-1.5 border border-transparent rounded-md text-xs sm:text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Search
                </button>
            </div>
        </div>
    </div>

    <!-- Export Options - Top Right -->
    <div class="mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-1 sm:gap-2">
            <a id="exportAnswersBtn" href="#" class="bg-blue-600 text-white px-3 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">
                Download Jawaban CSV
            </a>
        </div>
    </div>

    <!-- Tabel Jawaban -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table id="answersTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Sesi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Assessment</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jawaban/Data</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="answersTableBody" class="bg-white divide-y divide-gray-200 text-sm">
                    @php $row = 1; @endphp
                    @foreach($sessions as $session)
                        @foreach($session->participants as $participant)
                            @php $peserta = $participant->peserta; @endphp
                            @foreach($session->assessments as $sessionAssessment)
                                @php 
                                    $penilaian = $sessionAssessment->penilaian;
                                    
                                    // Get progress
                                    $progress = \App\Models\KemajuanPenilaian::where('peserta_id', $peserta->id)
                                        ->where('penilaian_id', $penilaian->id)
                                        ->where('sesi_penilaian_id', $session->id)
                                        ->first();
                                    
                                    // Get answers based on assessment type
                                    $jawaban = '';
                                    $hasAnswer = false;
                                    
                                    switch ($penilaian->jenis) {
                                        case 'studi_kasus':
                                            $jawabanData = \App\Models\JawabanStudiKasus::where('peserta_id', $peserta->id)
                                                ->where('penilaian_id', $penilaian->id)
                                                ->where('sesi_penilaian_id', $session->id)
                                                ->first();
                                            if ($jawabanData) {
                                                $jawaban = Str::limit(strip_tags($jawabanData->jawaban), 100);
                                                $hasAnswer = true;
                                            }
                                            break;
                                            
                                case 'in_tray':
                                    $jawabanData = \App\Models\JawabanInTray::where('peserta_id', $peserta->id)
                                        ->where('penilaian_id', $penilaian->id)
                                        ->where('sesi_penilaian_id', $session->id)
                                        ->orderBy('urutan_prioritas', 'asc')
                                        ->get();
                                    if ($jawabanData->count() > 0) {
                                        // Cek model yang digunakan
                                        $model = $penilaian->model_in_tray ?? 'urutan';
                                        
                                        if ($model === 'prioritas') {
                                            $result = [];
                                            foreach ($jawabanData as $jawab) {
                                                $memoId = $jawab->latihan_in_tray_id;
                                                $prioritas = $jawab->prioritasMemo;
                                                $prioritasLabel = $prioritas ? $prioritas->priority_label : 'Belum dipilih';
                                                $disposisi = $jawab->disposisi ?: 'Belum ada disposisi';
                                                $result[] = '• memo-' . $memoId . ' | ' . $prioritasLabel . ' | ' . $disposisi;
                                            }
                                            $jawaban = implode('<br>', $result);
                                        } else {
                                            $memoIds = $jawabanData->pluck('latihan_in_tray_id')->toArray();
                                            $jawaban = 'memo-' . implode(', memo-', $memoIds);
                                        }
                                        $hasAnswer = true;
                                    }
                                    break;
                                            
                                        case 'roleplay':
                                            $jawabanData = \App\Models\CatatanRoleplay::where('peserta_id', $peserta->id)
                                                ->where('penilaian_id', $penilaian->id)
                                                ->where('sesi_penilaian_id', $session->id)
                                                ->first();
                                            if ($jawabanData) {
                                                $jawaban = Str::limit(strip_tags($jawabanData->catatan), 100);
                                                $hasAnswer = true;
                                            }
                                            break;
                                            
                                        case 'fgd':
                                            $jawabanData = \App\Models\CatatanFgd::where('peserta_id', $peserta->id)
                                                ->where('penilaian_id', $penilaian->id)
                                                ->where('sesi_penilaian_id', $session->id)
                                                ->first();
                                            if ($jawabanData) {
                                                $jawaban = Str::limit(strip_tags($jawabanData->catatan), 100);
                                                $hasAnswer = true;
                                            }
                                            break;
                                    }
                                    
                                    $status = $progress->status ?? 'belum_mulai';
                                    $statusColor = match($status) {
                                        'sedang_berlangsung' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'selesai' => 'bg-green-100 text-green-800 border-green-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200'
                                    };
                                    $statusText = $status === 'sedang_berlangsung' ? 'draft' : ($status === 'selesai' ? 'selesai' : 'belum');
                                @endphp
                                <tr class="answer-row" 
                                    data-sesi-nama="{{ strtolower($session->nama) }}" 
                                    data-peserta-nama="{{ strtolower($peserta->nama_lengkap) }}"
                                    data-instansi="{{ strtolower($peserta->instansi ?? '') }}"
                                    data-assessment-type="{{ $penilaian->jenis === 'in_tray' ? 'in_tray_' . ($penilaian->model_in_tray ?? 'urutan') : $penilaian->jenis }}"
                                    data-search-text="{{ strtolower($session->nama . ' ' . $peserta->nama_lengkap . ' ' . ($peserta->instansi ?? '') . ' ' . $penilaian->jenis . ' ' . ($penilaian->jenis === 'in_tray' ? ($penilaian->model_in_tray ?? 'urutan') : '')) }}">
                                    <td class="px-4 py-2">{{ $row++ }}</td>
                                    <td class="px-4 py-2">
                                        <span class="font-medium text-gray-900">{{ $session->nama }}</span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900">{{ $peserta->nama_lengkap }}</span>
                                            <span class="text-xs text-gray-500">{{ $peserta->email ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        @php
                                            $jenisDisplay = ucfirst(str_replace('_', ' ', $penilaian->jenis));
                                            if ($penilaian->jenis === 'in_tray') {
                                                $model = $penilaian->model_in_tray ?? 'urutan';
                                                $jenisDisplay .= ' (' . ($model === 'prioritas' ? 'Prioritas' : 'Urutan') . ')';
                                            }
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                            {{ $jenisDisplay }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-0.5 rounded-full text-xs {{ $statusColor }} border">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($hasAnswer)
                                            <div class="max-w-xs">
                                                <div class="text-gray-900 text-sm">{!! $jawaban !!}</div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">Belum ada jawaban</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex flex-col gap-1">
                                            @if($hasAnswer)
                                                <button data-action="view-detail" data-jenis="{{ $penilaian->jenis }}" data-peserta-id="{{ $peserta->id }}" data-penilaian-id="{{ $penilaian->id }}" data-sesi-id="{{ $session->id }}" 
                                                        class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 transition-colors">
                                                    Lihat Detail
                                                </button>
                                            @endif
                                            <button data-action="download-answer" data-peserta-id="{{ $peserta->id }}" data-penilaian-id="{{ $penilaian->id }}" data-sesi-id="{{ $session->id }}" 
                                                    class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded hover:bg-green-200 transition-colors">
                                                Download
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($sessions->hasPages())
    <div class="mt-6 flex items-center justify-between">
        <div class="flex items-center text-sm text-gray-700">
            <span>Menampilkan {{ $sessions->firstItem() ?? 0 }} sampai {{ $sessions->lastItem() ?? 0 }} dari {{ $sessions->total() }} data</span>
        </div>
        
        <div class="flex items-center space-x-2">
            <!-- Per Page Selector -->
            <div class="flex items-center space-x-2">
                <label for="perPageSelect" class="text-sm text-gray-700">Per halaman:</label>
                <select id="perPageSelect" class="px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            
            <!-- Pagination Links -->
            <div class="flex items-center space-x-1">
                {{-- Previous Page Link --}}
                @if ($sessions->onFirstPage())
                    <span class="px-3 py-1 text-sm text-gray-400 bg-gray-100 rounded cursor-not-allowed">Sebelumnya</span>
                @else
                    <a href="{{ $sessions->previousPageUrl() }}" class="px-3 py-1 text-sm text-blue-600 bg-white border border-gray-300 rounded hover:bg-gray-50">Sebelumnya</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($sessions->getUrlRange(1, $sessions->lastPage()) as $page => $url)
                    @if ($page == $sessions->currentPage())
                        <span class="px-3 py-1 text-sm text-white bg-blue-600 rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 text-sm text-blue-600 bg-white border border-gray-300 rounded hover:bg-gray-50">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($sessions->hasMorePages())
                    <a href="{{ $sessions->nextPageUrl() }}" class="px-3 py-1 text-sm text-blue-600 bg-white border border-gray-300 rounded hover:bg-gray-50">Selanjutnya</a>
                @else
                    <span class="px-3 py-1 text-sm text-gray-400 bg-gray-100 rounded cursor-not-allowed">Selanjutnya</span>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    <!-- No Results Message -->
    <div id="noResultsMessage" class="hidden text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
        <div class="text-gray-500 text-lg font-medium mb-2">Tidak ada data ditemukan</div>
        <div class="text-gray-400 text-sm">Coba gunakan kata kunci pencarian yang berbeda</div>
    </div>
</div>

<!-- Modal untuk melihat detail jawaban -->
<div id="answerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Detail Jawaban</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="text-sm text-gray-700">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Elements
    const exportAnswersBtn = document.getElementById('exportAnswersBtn');
    const answersTable = document.getElementById('answersTable');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const universalSearch = document.getElementById('universalSearch');
    const sessionFilter = document.getElementById('sessionFilter');
    const assessmentTypeFilter = document.getElementById('assessmentTypeFilter');
    const participantNameFilter = document.getElementById('participantNameFilter');
    const institutionFilter = document.getElementById('institutionFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    
    let searchTimeout;
    let currentFilters = {
        universalSearch: '',
        session: '',
        assessmentType: '',
        participantName: '',
        institution: ''
    };
    
    // Export Answers functionality
    if (exportAnswersBtn) {
        exportAnswersBtn.addEventListener('click', function(e){
            e.preventDefault();
            const params = buildFilterParams();
            window.location.href = '{{ route("admin.progress.export-answers") }}' + params;
        });
    }
    
    // Apply filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            currentFilters.universalSearch = universalSearch.value.trim();
            currentFilters.session = sessionFilter.value;
            currentFilters.assessmentType = assessmentTypeFilter.value;
            currentFilters.participantName = participantNameFilter.value;
            currentFilters.institution = institutionFilter.value;
            applyFilters();
        });
    }
    
    // Reset filters
    if (resetFilters) {
        resetFilters.addEventListener('click', function() {
            universalSearch.value = '';
            sessionFilter.value = '';
            assessmentTypeFilter.value = '';
            participantNameFilter.value = '';
            institutionFilter.value = '';
            currentFilters = { universalSearch: '', session: '', assessmentType: '', participantName: '', institution: '' };
            applyFilters();
        });
    }
    
    // Universal search functionality
    if (universalSearch) {
    let searchTimeout;
        universalSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentFilters.universalSearch = this.value.trim();
                applyFilters();
            }, 500);
        });
    }
    
    function buildFilterParams() {
        let params = '';
        if (currentFilters.universalSearch) params += '&universal_search=' + encodeURIComponent(currentFilters.universalSearch);
        if (currentFilters.session) params += '&session=' + encodeURIComponent(currentFilters.session);
        if (currentFilters.assessmentType) params += '&assessment_type=' + encodeURIComponent(currentFilters.assessmentType);
        if (currentFilters.participantName) params += '&participant_name=' + encodeURIComponent(currentFilters.participantName);
        if (currentFilters.institution) params += '&institution=' + encodeURIComponent(currentFilters.institution);
        return params;
    }
    
    function applyFilters() {
        const allRows = document.querySelectorAll('.answer-row');
        let visibleCount = 0;
        
        allRows.forEach(row => {
            const sesiNama = row.getAttribute('data-sesi-nama');
            const assessmentType = row.getAttribute('data-assessment-type');
            const pesertaNama = row.getAttribute('data-peserta-nama');
            const instansi = row.getAttribute('data-instansi');
            const searchText = row.getAttribute('data-search-text');
            
            // Check universal search
            const universalMatch = !currentFilters.universalSearch || searchText.includes(currentFilters.universalSearch.toLowerCase());
            
            // Check session filter
            const sessionMatch = !currentFilters.session || sesiNama === currentFilters.session.toLowerCase();
            
            // Check assessment type filter
            let assessmentTypeMatch = true;
            if (currentFilters.assessmentType) {
                if (currentFilters.assessmentType === 'in_tray') {
                    // Filter untuk semua model in-tray
                    assessmentTypeMatch = assessmentType.startsWith('in_tray');
                } else if (currentFilters.assessmentType === 'in_tray_urutan') {
                    // Filter untuk model urutan
                    assessmentTypeMatch = assessmentType === 'in_tray_urutan';
                } else if (currentFilters.assessmentType === 'in_tray_prioritas') {
                    // Filter untuk model prioritas
                    assessmentTypeMatch = assessmentType === 'in_tray_prioritas';
                } else {
                    // Filter untuk jenis assessment lainnya
                    assessmentTypeMatch = assessmentType === currentFilters.assessmentType;
                }
            }
            
            // Check participant name filter
            const participantMatch = !currentFilters.participantName || pesertaNama.includes(currentFilters.participantName.toLowerCase());
            
            // Check institution filter
            const institutionMatch = !currentFilters.institution || instansi.includes(currentFilters.institution.toLowerCase());
            
            if (universalMatch && sessionMatch && assessmentTypeMatch && participantMatch && institutionMatch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0) {
            noResultsMessage.classList.remove('hidden');
        } else {
            noResultsMessage.classList.add('hidden');
        }
    }
    
    // Action buttons functionality
    document.addEventListener('click', function(e) {
        if (e.target.getAttribute('data-action') === 'view-detail') {
            e.preventDefault();
            const jenis = e.target.getAttribute('data-jenis');
            const pesertaId = e.target.getAttribute('data-peserta-id');
            const penilaianId = e.target.getAttribute('data-penilaian-id');
            const sesiId = e.target.getAttribute('data-sesi-id');
            
            console.log('View detail clicked:', { jenis, pesertaId, penilaianId, sesiId });
            
            // Show modal with answer details
            const modal = document.getElementById('answerModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            
            if (!modal || !modalTitle || !modalContent) {
                console.error('Modal elements not found');
                return;
            }
            
            modalTitle.textContent = 'Detail Jawaban - ' + jenis.replace('_', ' ').toUpperCase();
            
            // Load answer content based on type
            const url = '{{ route("admin.progress.answer-detail") }}?jenis=' + jenis + '&peserta_id=' + pesertaId + '&penilaian_id=' + penilaianId + '&sesi_id=' + sesiId;
            console.log('Fetching URL:', url);
            
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    modalContent.innerHTML = data.content;
                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error loading answer detail:', error);
                    modalContent.innerHTML = '<p class="text-red-500">Error loading answer details: ' + error.message + '</p>';
                    modal.classList.remove('hidden');
                });
        }
        
        if (e.target.getAttribute('data-action') === 'download-answer') {
            e.preventDefault();
            const pesertaId = e.target.getAttribute('data-peserta-id');
            const penilaianId = e.target.getAttribute('data-penilaian-id');
            const sesiId = e.target.getAttribute('data-sesi-id');
            
            window.location.href = '{{ route("admin.progress.export-answers") }}?peserta_id=' + pesertaId + '&penilaian_id=' + penilaianId + '&sesi_id=' + sesiId;
        }
    });
    
    window.closeModal = function() {
        document.getElementById('answerModal').classList.add('hidden');
    };
    
    // Close modal when clicking outside
    document.getElementById('answerModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Per page selector functionality
    const perPageSelect = document.getElementById('perPageSelect');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('per_page', this.value);
            currentUrl.searchParams.set('page', '1'); // Reset to first page
            window.location.href = currentUrl.toString();
        });
    }
});
</script>
@endsection
