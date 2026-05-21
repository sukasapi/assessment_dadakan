@extends('admin.layouts.app')

@section('title', 'Jawaban Peserta')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Jawaban Peserta Assessment',
    'subtitle' => 'Lihat dan nilai jawaban peserta per assessment',
    'actions' => '<a href="' . route('admin.progress.index') . '" class="admin-btn-secondary">← Kembali ke Progress</a>',
])

    <!-- Filter Card -->
    <div class="mb-2 sm:mb-4">
        <div class="admin-card p-2 sm:p-4">
            <!-- Universal Search Bar -->
            <div class="mb-2 sm:mb-3">
            <div class="relative">
                <input type="text" 
                           id="universalSearch" 
                           placeholder="Cari berdasarkan nama sesi, nama peserta, instansi, dll..."
                           class="w-full px-3 py-2 pl-9 admin-input text-sm">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <label class="block text-xs sm:text-sm font-medium text-primary mb-1">Nama Sesi</label>
                        <select id="sessionFilter" 
                                class="w-full px-2 sm:px-3 py-1.5 sm:py-2 admin-input text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ request('sesi_id') ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ request('sesi_id') ? 'disabled' : '' }}>
                            <option value="">Semua Sesi</option>
                            @foreach(\App\Models\SesiPenilaian::orderBy('nama')->get() as $sesi)
                                <option value="{{ $sesi->nama }}" 
                                        {{ (request('sesi_id') && request('sesi_id') == $sesi->id) || request('session') == $sesi->nama ? 'selected' : '' }}>
                                    {{ $sesi->nama }}
                                </option>
                            @endforeach
                        </select>
                        @if(request('sesi_id'))
                            <p class="mt-1 text-xs text-tertiary">Filter dinonaktifkan karena sesi sudah dipilih</p>
                        @endif
                    </div>
                    
                    <!-- Filter Jenis Assessment -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-primary mb-1">Jenis Assessment</label>
                        <select id="assessmentTypeFilter" class="w-full px-2 sm:px-3 py-1.5 sm:py-2 admin-input text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Jenis</option>
                            <option value="studi_kasus">Studi Kasus</option>
                            <option value="in_tray">In-Tray (Semua Model)</option>
                            <option value="in_tray_urutan">In-Tray (Model Urutan)</option>
                            <option value="in_tray_prioritas">In-Tray (Model Prioritas)</option>
                            <option value="roleplay">Role-Play</option>
                            <option value="fgd">LGD/FGD</option>
                        </select>
                    </div>
                    
                    <!-- Filter Status Jawaban (untuk Studi Kasus) -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-primary mb-1">Status Jawaban</label>
                        <select id="jawabanStatusFilter" class="w-full px-2 sm:px-3 py-1.5 sm:py-2 admin-input text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="final">Final - Bisa Dinilai</option>
                            <option value="draft">Draft - Belum Final</option>
                        </select>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-2 sm:space-y-3">
                    <!-- Filter Nama Peserta -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-primary mb-1">Nama Peserta</label>
                        <input type="text" 
                               id="participantNameFilter" 
                               placeholder="Masukkan nama peserta..."
                               value="{{ request('peserta_id') ? \App\Models\Peserta::find(request('peserta_id'))->nama_lengkap ?? '' : '' }}"
                               class="w-full px-2 sm:px-3 py-1.5 sm:py-2 admin-input text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ request('peserta_id') ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                               {{ request('peserta_id') ? 'readonly' : '' }}>
                        @if(request('peserta_id'))
                            <p class="mt-1 text-xs text-tertiary">Filter dinonaktifkan karena peserta sudah dipilih</p>
                        @endif
                    </div>
                    
                    <!-- Filter Instansi -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-primary mb-1">Instansi</label>
                        <input type="text" 
                               id="institutionFilter" 
                               placeholder="Masukkan nama instansi..."
                               class="w-full px-2 sm:px-3 py-1.5 sm:py-2 admin-input text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-1 sm:gap-2">
                <button id="resetFilters" class="w-full sm:w-auto px-2 sm:px-3 py-1 sm:py-1.5 admin-input text-xs sm:text-sm font-medium text-primary bg-white hover:bg-neutral focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Reset
                </button>
                <button id="applyFilters" class="w-full sm:w-auto px-2 sm:px-3 py-1 sm:py-1.5 border border-transparent rounded-md text-xs sm:text-sm font-medium text-white admin-btn-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Search
                </button>
            </div>
        </div>
    </div>

    <!-- Export Options - Top Right -->
    <div class="mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-1 sm:gap-2">
            <a id="exportAnswersBtn" href="#" class="admin-btn-primary text-sm transition-colors">
                Download Jawaban CSV
            </a>
        </div>
    </div>

    <!-- Tabel Jawaban -->
    <div class="admin-card">
        <div class="admin-card-table-inner">
            <table id="answersTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">Nama Sesi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">Nama Peserta</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">Jenis Assessment</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">Jawaban/Data</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="answersTableBody" class="bg-white divide-y divide-gray-200 text-sm">
                    @php $row = 1; @endphp
                    @foreach($sessions as $session)
                        @php
                            // Filter participants based on request parameters
                            $filteredParticipants = $session->participants;
                            
                            // If peserta_id is specified, only show that participant
                            if (request('peserta_id')) {
                                $filteredParticipants = $filteredParticipants->where('peserta_id', request('peserta_id'));
                            }
                        @endphp
                        @foreach($filteredParticipants as $participant)
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
                                                ->where(function($q) use ($session) {
                                                    $q->where('sesi_penilaian_id', $session->id)
                                                      ->orWhereNull('sesi_penilaian_id');
                                                })
                                                ->first();
                                            if ($jawabanData) {
                                                $jawaban = Str::limit(strip_tags($jawabanData->jawaban), 100);
                                                $hasAnswer = true;
                                                
                                                // Simpan status jawaban untuk ditampilkan di tabel
                                                $jawabanStatus = $jawabanData->status; // 'draft' atau 'final'
                                                
                                                // Cek apakah sudah dinilai
                                                $sudahDinilai = \App\Models\PenilaianStudiKasus::where('jawaban_studi_kasus_id', $jawabanData->id)
                                                    ->orWhere(function($q) use ($peserta, $penilaian, $session) {
                                                        $q->where('peserta_id', $peserta->id)
                                                          ->where('penilaian_id', $penilaian->id)
                                                          ->where('sesi_penilaian_id', $session->id);
                                                    })
                                                    ->exists();
                                            } else {
                                                $sudahDinilai = false;
                                                $jawabanStatus = null;
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
                                                $result[] = '• memo-' . $memoId . ' | ' . e($prioritasLabel) . ' | ' . e($disposisi);
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
                                    
                                    // Untuk studi kasus, prioritaskan status dari jawaban_studi_kasus atau kemajuan_penilaian
                                    if ($penilaian->jenis === 'studi_kasus') {
                                        // Jika jawaban sudah final, status adalah selesai
                                        if (isset($jawabanStatus) && $jawabanStatus === 'final') {
                                            $status = 'selesai';
                                        } 
                                        // Jika kemajuan_penilaian sudah selesai, status adalah selesai
                                        elseif ($progress && $progress->status === 'selesai') {
                                            $status = 'selesai';
                                        } 
                                        // Jika masih draft, gunakan status dari kemajuan_penilaian
                                        else {
                                            $status = $progress->status ?? 'belum_mulai';
                                        }
                                    } else {
                                        // Untuk jenis assessment lain, gunakan status dari kemajuan_penilaian
                                        $status = $progress->status ?? 'belum_mulai';
                                    }
                                    
                                    $statusColor = match($status) {
                                        'sedang_berlangsung' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'selesai' => 'bg-green-100 text-green-800 border-green-200',
                                        default => 'bg-gray-100 text-primary border-gray-200'
                                    };
                                    $statusText = $status === 'sedang_berlangsung' ? 'draft' : ($status === 'selesai' ? 'selesai' : 'belum');
                                @endphp
                                <tr class="answer-row" 
                                    data-sesi-nama="{{ strtolower($session->nama) }}" 
                                    data-peserta-nama="{{ strtolower($peserta->nama_lengkap) }}"
                                    data-instansi="{{ strtolower($peserta->instansi ?? '') }}"
                                    data-assessment-type="{{ $penilaian->jenis === 'in_tray' ? 'in_tray_' . ($penilaian->model_in_tray ?? 'urutan') : $penilaian->jenis }}"
                                    data-jawaban-status="{{ $penilaian->jenis === 'studi_kasus' && isset($jawabanStatus) ? $jawabanStatus : '' }}"
                                    data-search-text="{{ strtolower($session->nama . ' ' . $peserta->nama_lengkap . ' ' . ($peserta->instansi ?? '') . ' ' . $penilaian->jenis . ' ' . ($penilaian->jenis === 'in_tray' ? ($penilaian->model_in_tray ?? 'urutan') : '')) }}">
                                    <td class="px-4 py-2">{{ $row++ }}</td>
                                    <td class="px-4 py-2">
                                        <span class="font-medium text-primary">{{ $session->nama }}</span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-primary">{{ $peserta->nama_lengkap }}</span>
                                            <span class="text-xs text-tertiary">{{ $peserta->email ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        @php
                                            // Gunakan jenis_text untuk menampilkan nama lengkap (misalnya "Studi Kasus BQ", "Studi Kasus PQ", "LGD")
                                            $jenisDisplay = $penilaian->jenis_text;
                                            // Untuk in_tray, tambahkan model (urutan/prioritas)
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
                                            <div class="max-w-xs space-y-1">
                                                <div class="text-primary text-sm">{!! nl2br(e($jawaban)) !!}</div>
                                                @if($penilaian->jenis === 'studi_kasus' && isset($jawabanStatus))
                                                    @if($jawabanStatus === 'final')
                                                        @if(isset($sudahDinilai) && $sudahDinilai)
                                                            <span class="inline-block px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">
                                                                ✓ Final - Telah Dinilai
                                                            </span>
                                                        @else
                                                            <span class="inline-block px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">
                                                                ✓ Final - Bisa Dinilai
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="inline-block px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">
                                                            ⏳ Draft - Belum Final
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-tertiary text-sm">Belum ada jawaban</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex flex-col gap-1">
                                            @if($hasAnswer)
                                                <button data-action="view-detail" data-jenis="{{ $penilaian->jenis }}" data-peserta-id="{{ $peserta->id }}" data-penilaian-id="{{ $penilaian->id }}" data-sesi-id="{{ $session->id }}" 
                                                        class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 transition-colors">
                                                    Lihat Detail
                                                </button>
                                                @if($penilaian->jenis === 'studi_kasus' && isset($sudahDinilai) && $sudahDinilai)
                                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded text-center">
                                                        ✓ Sudah Dinilai
                                                    </span>
                                                @elseif($penilaian->jenis === 'studi_kasus' && isset($sudahDinilai) && !$sudahDinilai)
                                                    <span class="text-xs px-2 py-1 bg-gray-100 text-tertiary rounded text-center">
                                                        Belum Dinilai
                                                    </span>
                                                @endif
                                            @endif
                                            @if(($penilaian->jenis === 'studi_kasus' || $penilaian->jenis === 'roleplay' || $penilaian->jenis === 'fgd') && $penilaian->file_pdf)
                                                <button type="button"
                                                        data-action="view-pdf"
                                                        data-penilaian-id="{{ $penilaian->id }}"
                                                        data-pdf-url="{{ $penilaian->file_pdf ? asset('storage/' . $penilaian->file_pdf) : '' }}"
                                                        class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded hover:bg-purple-200 transition-colors">
                                                    📄 Lihat PDF
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
        <div class="flex items-center text-sm text-primary">
            <span>Menampilkan {{ $sessions->firstItem() ?? 0 }} sampai {{ $sessions->lastItem() ?? 0 }} dari {{ $sessions->total() }} data</span>
        </div>
        
        <div class="flex items-center space-x-2">
            <!-- Per Page Selector -->
            <div class="flex items-center space-x-2">
                <label for="perPageSelect" class="text-sm text-primary">Per halaman:</label>
                <select id="perPageSelect" class="px-2 py-1 admin-input text-sm">
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
                    <span class="px-3 py-1 text-sm text-tertiary bg-gray-100 rounded cursor-not-allowed">Sebelumnya</span>
                @else
                    <a href="{{ $sessions->previousPageUrl() }}" class="px-3 py-1 text-sm text-blue-600 bg-white border border-gray-300 rounded hover:bg-neutral">Sebelumnya</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($sessions->getUrlRange(1, $sessions->lastPage()) as $page => $url)
                    @if ($page == $sessions->currentPage())
                        <span class="px-3 py-1 text-sm text-white bg-blue-600 rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 text-sm text-blue-600 bg-white border border-gray-300 rounded hover:bg-neutral">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($sessions->hasMorePages())
                    <a href="{{ $sessions->nextPageUrl() }}" class="px-3 py-1 text-sm text-blue-600 bg-white border border-gray-300 rounded hover:bg-neutral">Selanjutnya</a>
                @else
                    <span class="px-3 py-1 text-sm text-tertiary bg-gray-100 rounded cursor-not-allowed">Selanjutnya</span>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    <!-- No Results Message -->
    <div id="noResultsMessage" class="hidden text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
        <div class="text-tertiary text-lg font-medium mb-2">Tidak ada data ditemukan</div>
        <div class="text-tertiary text-sm">Coba gunakan kata kunci pencarian yang berbeda</div>
    </div>

@push('modals')
<!-- Modal untuk melihat detail jawaban -->
<div id="answerModal" class="admin-modal hidden" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="admin-modal-panel admin-modal-panel-lg">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-primary" id="modalTitle">Detail Jawaban</h3>
                <button onclick="closeModal()" class="text-tertiary hover:text-tertiary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="text-sm text-primary">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk melihat PDF -->
<div id="pdfViewerModal" class="admin-modal hidden" role="dialog" aria-modal="true">
    <div class="admin-modal-panel admin-modal-panel-pdf">
        <div class="flex justify-between items-center gap-3 mb-3 flex-shrink-0">
            <h3 class="text-lg font-medium text-primary">Preview PDF</h3>
            <div class="flex items-center gap-2">
                <a id="pdfViewerOpenTab" href="#" target="_blank" rel="noopener noreferrer" class="admin-btn-secondary text-sm whitespace-nowrap">
                    Buka di Tab Baru
                </a>
                <button type="button" onclick="closePdfViewer()" class="p-2 text-tertiary hover:text-primary rounded-lg hover:bg-neutral" aria-label="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="pdfViewerContent" class="admin-pdf-preview-body">
            <div class="admin-pdf-preview-loading">Memuat PDF...</div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
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
    const jawabanStatusFilter = document.getElementById('jawabanStatusFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    
    let searchTimeout;
    let currentFilters = {
        universalSearch: '',
        session: '',
        assessmentType: '',
        participantName: '',
        institution: '',
        jawabanStatus: ''
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
            currentFilters.jawabanStatus = jawabanStatusFilter ? jawabanStatusFilter.value : '';
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
            if (jawabanStatusFilter) jawabanStatusFilter.value = '';
            currentFilters = { universalSearch: '', session: '', assessmentType: '', participantName: '', institution: '', jawabanStatus: '' };
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
            const jawabanStatus = row.getAttribute('data-jawaban-status');
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
            
            // Check jawaban status filter (hanya untuk studi kasus)
            let jawabanStatusMatch = true;
            if (currentFilters.jawabanStatus) {
                // Hanya filter jika assessment type adalah studi_kasus
                if (assessmentType === 'studi_kasus') {
                    jawabanStatusMatch = jawabanStatus === currentFilters.jawabanStatus;
                } else {
                    // Untuk non-studi-kasus, selalu match (tidak ada filter status)
                    jawabanStatusMatch = true;
                }
            }
            
            if (universalMatch && sessionMatch && assessmentTypeMatch && participantMatch && institutionMatch && jawabanStatusMatch) {
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
    
    // Action buttons functionality (delegasi — klik teks/svg di dalam tombol tetap jalan)
    document.addEventListener('click', function(e) {
        const viewDetailBtn = e.target.closest('[data-action="view-detail"]');
        if (viewDetailBtn) {
            e.preventDefault();
            const jenis = viewDetailBtn.getAttribute('data-jenis');
            const pesertaId = viewDetailBtn.getAttribute('data-peserta-id');
            const penilaianId = viewDetailBtn.getAttribute('data-penilaian-id');
            const sesiId = viewDetailBtn.getAttribute('data-sesi-id');
            
            // Show modal with answer details
            const modal = document.getElementById('answerModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            
            if (!modal || !modalTitle || !modalContent) {
                return;
            }
            
            modalTitle.textContent = 'Detail Jawaban - ' + jenis.replace('_', ' ').toUpperCase();
            
            // Load answer content based on type
            const url = '{{ route("admin.progress.answer-detail") }}?jenis=' + jenis + '&peserta_id=' + pesertaId + '&penilaian_id=' + penilaianId + '&sesi_id=' + sesiId;
            
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    modalContent.innerHTML = data.content;
                    adminOpenModal(modal);
                    
                    // Inisialisasi form penilaian studi kasus setelah content dimuat
                    // Gunakan beberapa timeout untuk memastikan DOM sudah siap
                    let initAttempts = 0;
                    const maxAttempts = 5;
                    
                    function tryInit() {
                        initAttempts++;
                        const success = initPenilaianForm();
                        if (!success && initAttempts < maxAttempts) {
                            setTimeout(tryInit, 200);
                        }
                    }
                    
                    setTimeout(tryInit, 100);
                    setTimeout(tryInit, 300);
                    setTimeout(tryInit, 500);
                    setTimeout(tryInit, 1000);
                    
                        // Initialize Summernote untuk textarea catatan setelah modal dibuka
                        setTimeout(function() {
                            const catatanEditor = modalContent.querySelector('.catatan-penilaian-editor');
                            const form = modalContent.querySelector('#formPenilaianStudiKasus');
                            if (catatanEditor && catatanEditor.id && window.initCKEditor) {
                                // Cek apakah form disabled (status draft)
                                const isFinal = form ? (form.getAttribute('data-is-final') === '1') : true;
                                
                                // Destroy existing instance if any
                                if (window.ckeditorInstances && window.ckeditorInstances[catatanEditor.id]) {
                                    try {
                                        if (window.$ && window.$.fn.summernote) {
                                            $('#' + catatanEditor.id).summernote('destroy');
                                        }
                                        delete window.ckeditorInstances[catatanEditor.id];
                                    } catch (e) {
                                        // Silent fail
                                    }
                                }
                                
                                // Initialize new instance
                                window.initCKEditor(catatanEditor.id);
                                
                                // Disable Summernote jika status draft
                                if (!isFinal && window.$ && window.$.fn.summernote) {
                                    setTimeout(function() {
                                        try {
                                            $('#' + catatanEditor.id).summernote('disable');
                                        } catch (e) {
                                            // Silent fail
                                        }
                                    }, 100);
                                }
                            }
                        }, 300);
                })
                .catch(error => {
                    modalContent.innerHTML = '<p class="text-red-500">Error loading answer details: ' + error.message + '</p>';
                    adminOpenModal(modal);
                });
        }
        
        const downloadBtn = e.target.closest('[data-action="download-answer"]');
        if (downloadBtn) {
            e.preventDefault();
            const pesertaId = downloadBtn.getAttribute('data-peserta-id');
            const penilaianId = downloadBtn.getAttribute('data-penilaian-id');
            const sesiId = downloadBtn.getAttribute('data-sesi-id');
            
            window.location.href = '{{ route("admin.progress.export-answers") }}?peserta_id=' + pesertaId + '&penilaian_id=' + penilaianId + '&sesi_id=' + sesiId;
        }
        
        const viewPdfBtn = e.target.closest('[data-action="view-pdf"]');
        if (viewPdfBtn) {
            e.preventDefault();
            const pdfUrl = viewPdfBtn.getAttribute('data-pdf-url');

            if (!pdfUrl) {
                showNotification('URL PDF tidak tersedia.', 'error');
                return;
            }

            adminShowPdfPreview({
                modalId: 'pdfViewerModal',
                contentId: 'pdfViewerContent',
                openTabId: 'pdfViewerOpenTab',
                pdfUrl: pdfUrl,
            });
        }
    });
    
    window.closeModal = function () {
        adminCloseModal('answerModal');
    };

    window.closePdfViewer = function () {
        adminClosePdfPreview('pdfViewerModal');
    };

    const answerModalEl = document.getElementById('answerModal');
    if (answerModalEl) {
        answerModalEl.addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }

    const pdfViewerModalEl = document.getElementById('pdfViewerModal');
    if (pdfViewerModalEl) {
        pdfViewerModalEl.addEventListener('click', function (e) {
            if (e.target === this) {
                closePdfViewer();
            }
        });
    }
    
    // Handle form penilaian studi kasus
    document.addEventListener('click', function(e) {
        const saveBtn = e.target.closest('[data-action="save-draft"], [data-action="save-final"]');
        if (saveBtn) {
            e.preventDefault();
            const form = document.getElementById('formPenilaianStudiKasus');
            if (!form) {
                return;
            }
            
            // Cek apakah form disabled (status draft)
            const isFinal = form.getAttribute('data-is-final') === '1';
            const buttonIsFinal = saveBtn.getAttribute('data-is-final') === '1';
            
            if (!isFinal || !buttonIsFinal) {
                alert('Jawaban peserta masih dalam status draft. Penilaian hanya dapat dilakukan setelah peserta menyimpan jawaban sebagai final.');
                return;
            }
            
            const status = saveBtn.getAttribute('data-action') === 'save-final' ? 'final' : 'draft';
            submitPenilaianStudiKasus(form, status);
        }
    });
    
    // Handle perubahan dropdown kategori menggunakan event delegation
    // Gunakan event delegation untuk menangkap event dari elemen yang di-load dinamis
    // Hanya handle jika kategori masih berupa dropdown (bukan read-only)
    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'kategori_studi_kasus_id' && e.target.tagName === 'SELECT') {
            const form = e.target.closest('#formPenilaianStudiKasus');
            if (!form) {
                return;
            }
            
            const selectedKategoriId = e.target.value;
            showKategoriForm(selectedKategoriId, form);
        }
    });
    
    // Function untuk inisialisasi form setelah dimuat
    function initPenilaianForm() {
        const form = document.getElementById('formPenilaianStudiKasus');
        if (!form) {
            return false;
        }
        
        // Cek apakah kategori ditampilkan sebagai dropdown atau read-only (hidden input)
        const kategoriSelect = form.querySelector('select[name="kategori_studi_kasus_id"]');
        const kategoriHiddenInput = form.querySelector('input[type="hidden"][name="kategori_studi_kasus_id"]');
        
        if (!kategoriSelect && !kategoriHiddenInput) {
            return false;
        }
        
        let selectedKategoriId = null;
        
        // Jika kategori dari dropdown (sistem lama atau belum diupdate)
        if (kategoriSelect) {
            selectedKategoriId = kategoriSelect.value;
            
            // Jika sudah ada nilai yang dipilih, tampilkan form aspek penilaian
            if (selectedKategoriId) {
                showKategoriForm(selectedKategoriId, form);
            }
            
            // Pastikan event listener sudah terpasang
            // Hapus listener lama jika ada (dengan clone node)
            const oldSelect = kategoriSelect;
            const newSelect = oldSelect.cloneNode(true);
            oldSelect.parentNode.replaceChild(newSelect, oldSelect);
            
            // Tambahkan event listener baru
            newSelect.addEventListener('change', function() {
                const selectedKategoriId = this.value;
                showKategoriForm(selectedKategoriId, form);
            });
            
            // Jika sudah ada nilai yang dipilih, tampilkan form
            if (newSelect.value) {
                showKategoriForm(newSelect.value, form);
            }
        } 
        // Jika kategori dari hidden input (sudah dipilih di sesi - read-only)
        else if (kategoriHiddenInput) {
            selectedKategoriId = kategoriHiddenInput.value;
            
            // Langsung tampilkan form aspek penilaian
            if (selectedKategoriId) {
                showKategoriForm(selectedKategoriId, form);
            }
        }
        
        return true;
    }
    
    // Function untuk menampilkan form aspek penilaian berdasarkan kategori yang dipilih
    function showKategoriForm(selectedKategoriId, form) {
        if (!selectedKategoriId) {
            // Jika tidak ada kategori yang dipilih, sembunyikan semua form
            const formAspekPenilaian = form.querySelector('#form-aspek-penilaian');
            if (formAspekPenilaian) {
                const kategoriForms = formAspekPenilaian.querySelectorAll('.kategori-form');
                kategoriForms.forEach(kategoriForm => {
                    kategoriForm.classList.add('hidden');
                });
            }
            return;
        }
        
        const formAspekPenilaian = form.querySelector('#form-aspek-penilaian');
        if (!formAspekPenilaian) {
            return;
        }
        
        const kategoriForms = formAspekPenilaian.querySelectorAll('.kategori-form');
        
        if (kategoriForms.length === 0) {
            return;
        }
        
        let found = false;
        kategoriForms.forEach(kategoriForm => {
            const kategoriId = kategoriForm.getAttribute('data-kategori-id');
            if (kategoriId === selectedKategoriId) {
                kategoriForm.classList.remove('hidden');
                found = true;
                // Scroll ke form yang ditampilkan
                setTimeout(() => {
                    kategoriForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            } else {
                kategoriForm.classList.add('hidden');
            }
        });
    }
    
    // Inisialisasi saat DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initPenilaianForm();
    });
    
    // Juga inisialisasi setelah beberapa detik (untuk form yang di-load via AJAX)
    setTimeout(function() {
        initPenilaianForm();
    }, 500);
    
    function submitPenilaianStudiKasus(form, status) {
        // Deteksi sistem: cek apakah form menggunakan sistem lama atau baru
        const isOldSystem = form.getAttribute('data-is-old-system') === '1';
        
        if (isOldSystem) {
            // VALIDASI SISTEM LAMA
            const pertanyaan1 = form.querySelector('input[name="pertanyaan_1"]:checked');
            const pertanyaan2 = form.querySelector('input[name="pertanyaan_2"]:checked');
            const pertanyaan3 = form.querySelector('input[name="pertanyaan_3"]:checked');
            
            if (!pertanyaan1 || !pertanyaan2 || !pertanyaan3) {
                alert('Mohon lengkapi semua pertanyaan penilaian!');
                return;
            }
        } else {
            // VALIDASI SISTEM BARU
            // Cek sesi_id: validasi kategori hanya berlaku untuk sesi_id > 12
            const sesiPenilaianIdInput = form.querySelector('input[name="sesi_penilaian_id"]');
            const sesiId = sesiPenilaianIdInput ? parseInt(sesiPenilaianIdInput.value) : 0;
            const useNewSystem = sesiId > 12;
            
            // Definisikan kategoriId di scope yang lebih luas agar bisa digunakan di semua blok
            let kategoriId = null;
            
            // Validasi: pastikan kategori dipilih (hanya untuk sesi_id > 12)
            if (useNewSystem) {
                const kategoriSelect = form.querySelector('select[name="kategori_studi_kasus_id"]');
                const kategoriHiddenInput = form.querySelector('input[type="hidden"][name="kategori_studi_kasus_id"]');
                
                if (kategoriSelect && kategoriSelect.value) {
                    kategoriId = kategoriSelect.value;
                } else if (kategoriHiddenInput && kategoriHiddenInput.value) {
                    kategoriId = kategoriHiddenInput.value;
                }
                
                if (!kategoriId) {
                    alert('Kategori penilaian belum dipilih. Silakan edit sesi dan pilih kategori (BQ/PQ) untuk assessment studi kasus ini terlebih dahulu.');
                    return;
                }
            }
            
            // Validasi: pastikan semua aspek penilaian untuk kategori yang dipilih sudah dijawab (hanya untuk sesi_id > 12)
            if (useNewSystem && kategoriId) {
                const kategoriForm = form.querySelector('.kategori-form[data-kategori-id="' + kategoriId + '"]');
                if (!kategoriForm) {
                    alert('Form aspek penilaian untuk kategori yang dipilih tidak ditemukan!');
                    return;
                }
                
                const aspekInputs = kategoriForm.querySelectorAll('input[name^="aspek["]:checked');
                if (aspekInputs.length === 0) {
                    alert('Mohon lengkapi semua aspek penilaian untuk kategori yang dipilih!');
                    return;
                }
                
                // Validasi: pastikan semua aspek penilaian sudah dipilih (harus ada 6 aspek)
                const aspekIds = new Set();
                aspekInputs.forEach(input => {
                    const match = input.name.match(/aspek\[(\d+)\]/);
                    if (match) {
                        aspekIds.add(match[1]);
                    }
                });
                
                // Cek apakah ada aspek yang belum dipilih (harus ada 6 aspek)
                const totalAspek = kategoriForm.querySelectorAll('input[name^="aspek["]').length / 4; // 4 level per aspek
                if (aspekIds.size < totalAspek) {
                    alert('Mohon lengkapi semua aspek penilaian! Masih ada aspek yang belum dipilih.');
                    return;
                }
            }
        }
        
        // Disable buttons saat submit
        const buttons = form.querySelectorAll('button[data-action]');
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';
        });
        
        // Prepare form data
        const formData = new FormData(form);
        formData.append('status', status);
        
        if (!isOldSystem) {
            // SISTEM BARU: Pastikan kategori_studi_kasus_id terkirim (hanya untuk sesi_id > 12)
            const sesiPenilaianIdInput = form.querySelector('input[name="sesi_penilaian_id"]');
            const sesiId = sesiPenilaianIdInput ? parseInt(sesiPenilaianIdInput.value) : 0;
            const useNewSystem = sesiId > 12;
            
            if (useNewSystem) {
                const kategoriSelect = form.querySelector('select[name="kategori_studi_kasus_id"]');
                const kategoriHiddenInput = form.querySelector('input[type="hidden"][name="kategori_studi_kasus_id"]');
                
                let kategoriId = null;
                if (kategoriSelect && kategoriSelect.value) {
                    kategoriId = kategoriSelect.value;
                } else if (kategoriHiddenInput && kategoriHiddenInput.value) {
                    kategoriId = kategoriHiddenInput.value;
                }
                
                if (kategoriId) {
                    formData.set('kategori_studi_kasus_id', kategoriId);
                }
                
                // Pastikan hanya aspek dari kategori yang dipilih yang terkirim
                // Hapus semua aspek dari formData terlebih dahulu
                const allAspekInputs = form.querySelectorAll('input[name^="aspek["]');
                allAspekInputs.forEach(input => {
                    formData.delete(input.name);
                });
                
                // Tambahkan kembali hanya aspek dari kategori yang dipilih
                const kategoriForm = form.querySelector('.kategori-form[data-kategori-id="' + kategoriId + '"]');
                if (kategoriForm) {
                    const aspekInputs = kategoriForm.querySelectorAll('input[name^="aspek["]:checked');
                    aspekInputs.forEach(input => {
                        formData.append(input.name, input.value);
                    });
                }
            }
        }
        // SISTEM LAMA: pertanyaan_1, pertanyaan_2, pertanyaan_3 sudah otomatis terkirim via FormData
        
        // Get Summernote content untuk catatan jika menggunakan Summernote
        const catatanEditor = form.querySelector('.catatan-penilaian-editor');
        if (catatanEditor && catatanEditor.id && window.$ && window.$.fn.summernote) {
            try {
                const summernoteContent = $('#' + catatanEditor.id).summernote('code');
                formData.set('catatan', summernoteContent);
            } catch (e) {
                // Fallback ke textarea value jika Summernote tidak tersedia
                formData.set('catatan', catatanEditor.value || '');
            }
        }
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || form.querySelector('input[name="_token"]')?.value;
        if (csrfToken) {
            formData.set('_token', csrfToken);
        }
        
        // AJAX request
        fetch('{{ route("admin.progress.save-penilaian-studi-kasus") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success notification
                showNotification(data.message || 'Penilaian berhasil disimpan!', 'success');
                
                // Jika status adalah 'final', refresh halaman untuk update status "Belum Dinilai" / "Sudah Dinilai"
                if (status === 'final') {
                    // Tutup modal jika ada
                    const modal = form.closest('.modal, [role="dialog"]');
                    if (modal) {
                        // Cari dan klik tombol close modal
                        const closeBtn = modal.querySelector('[data-dismiss="modal"], .close, button[aria-label="Close"]');
                        if (closeBtn) {
                            closeBtn.click();
                        }
                    }
                    
                    // Refresh halaman setelah 1.5 detik untuk update status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Untuk status draft, hanya reload modal content setelah 1.5 detik
                    setTimeout(() => {
                        // Destroy Summernote instance sebelum reload
                        const catatanEditor = form.querySelector('.catatan-penilaian-editor');
                        if (catatanEditor && catatanEditor.id && window.$ && window.$.fn.summernote) {
                            try {
                                $('#' + catatanEditor.id).summernote('destroy');
                                if (window.ckeditorInstances && window.ckeditorInstances[catatanEditor.id]) {
                                    delete window.ckeditorInstances[catatanEditor.id];
                                }
                            } catch (e) {
                                // Silent fail
                            }
                        }
                        
                        // Reload modal dengan klik tombol view detail
                        const viewDetailBtn = document.querySelector('button[data-action="view-detail"]');
                        if (viewDetailBtn) {
                            viewDetailBtn.click();
                        }
                    }, 1500);
                }
            } else {
                // Show error notification
                showNotification(data.message || 'Gagal menyimpan penilaian. Silakan coba lagi.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat menyimpan penilaian. Silakan coba lagi.', 'error');
        })
        .finally(() => {
            // Re-enable buttons
            buttons.forEach(btn => {
                btn.disabled = false;
                if (btn.getAttribute('data-action') === 'save-draft') {
                    btn.textContent = 'Simpan Sementara';
                } else {
                    btn.textContent = 'Simpan Final';
                }
            });
        });
    }
    
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
    
    // Toast Notification function
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-xl transform transition-all duration-300 ease-in-out ${
            type === 'success' 
                ? 'bg-green-500 text-white border-l-4 border-green-600' 
                : 'bg-red-500 text-white border-l-4 border-red-600'
        }`;
        notification.style.transform = 'translateX(400px)';
        notification.style.opacity = '0';
        
        // Add icon based on type
        const icon = type === 'success' 
            ? '<svg class="w-5 h-5 mr-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
            : '<svg class="w-5 h-5 mr-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
        
        notification.innerHTML = icon + message;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 100);
        
        // Remove after 4 seconds with animation
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 4000);
    }
});
</script>
@endpush
@endsection
