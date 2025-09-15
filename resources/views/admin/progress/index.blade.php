@extends('admin.layouts.app')

@section('title', 'Daftar Progres Peserta')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Progres Pengerjaan Peserta</h1>

    <!-- Search dan Export -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <!-- Search Box -->
        <div class="flex-1 max-w-md">
            <div class="relative">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Cari berdasarkan nama sesi atau nama peserta (min 5 karakter)..."
                       class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div id="searchInfo" class="mt-1 text-xs text-gray-500 hidden">
                <span id="searchResultCount">0</span> hasil ditemukan
            </div>
        </div>
        
        <!-- Export CSV -->
        <div class="flex items-center gap-3">
            <label class="text-sm text-gray-700">Delimiter:</label>
            <select id="csvDelimiter" class="border-gray-300 rounded-md text-sm">
                <option value=",">Koma (,)</option>
                <option value=";">Titik koma (;)</option>
            </select>
            <a id="exportCsvBtn" href="#" class="bg-green-600 text-white px-3 py-2 rounded-md text-sm hover:bg-green-700">Export CSV</a>
        </div>
    </div>

    <!-- Tabel Progres Ringkas -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table id="progressTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Sesi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Studi Kasus</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">In‑Tray</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role‑Play</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LGD/FGD</th>
                    </tr>
                </thead>
                <tbody id="progressTableBody" class="bg-white divide-y divide-gray-200 text-sm">
                    @php $row = 1; @endphp
                    @foreach(\App\Models\SesiPenilaian::with(['participants.peserta','assessments.penilaian'])->orderBy('created_at','desc')->get() as $sesi)
                        @foreach($sesi->participants as $part)
                            @php 
                                $peserta = $part->peserta;
                                $mapJenis = ['studi_kasus'=>null,'in_tray'=>null,'roleplay'=>null,'role_play'=>null,'fgd'=>null];
                                foreach($sesi->assessments as $sa){ $mapJenis[$sa->penilaian->jenis] = $sa->penilaian->id; }
                                $statusBadge = function($penilaianId) use($peserta, $sesi){
                                    if(!$penilaianId) return '<span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600 border">tidak tersedia</span>';
                                    // Ambil progress berdasarkan penilaian_id dan sesi_penilaian_id (sama seperti dashboard user)
                                    $prog = \App\Models\KemajuanPenilaian::where('peserta_id',$peserta->id)
                                        ->where('penilaian_id',$penilaianId)
                                        ->where('sesi_penilaian_id',$sesi->id)
                                        ->first();
                                    $status = $prog->status ?? 'belum';
                                    $color = match($status){
                                        'sedang_berlangsung' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'selesai' => 'bg-green-100 text-green-800 border-green-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200'
                                    };
                                    $text = $status === 'sedang_berlangsung' ? 'draft' : ($status === 'selesai' ? 'selesai' : 'belum');
                                    return "<span class=\"px-2 py-0.5 rounded-full text-xs {$color} border\">{$text}</span>";
                                };
                            @endphp
                            <tr class="progress-row" 
                                data-sesi-nama="{{ strtolower($sesi->nama) }}" 
                                data-peserta-nama="{{ strtolower($peserta->nama_lengkap) }}"
                                data-search-text="{{ strtolower($sesi->nama . ' ' . $peserta->nama_lengkap) }}">
                                <td class="px-4 py-2">{{ $row++ }}</td>
                                <td class="px-4 py-2">{{ $sesi->nama }}</td>
                                <td class="px-4 py-2">{{ $peserta->nama_lengkap }}</td>
                                <td class="px-4 py-2">{{ $peserta->instansi ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $peserta->jabatan ?? '-' }}</td>
                                <td class="px-4 py-2">{!! $statusBadge($mapJenis['studi_kasus']) !!}</td>
                                <td class="px-4 py-2">{!! $statusBadge($mapJenis['in_tray']) !!}</td>
                                <td class="px-4 py-2">{!! $statusBadge($mapJenis['roleplay'] ?? $mapJenis['role_play']) !!}</td>
                                <td class="px-4 py-2">{!! $statusBadge($mapJenis['fgd']) !!}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- No Results Message -->
    <div id="noResultsMessage" class="hidden text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
        <div class="text-gray-500 text-lg font-medium mb-2">Tidak ada data ditemukan</div>
        <div class="text-gray-400 text-sm">Coba gunakan kata kunci pencarian yang berbeda</div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Export CSV functionality
    const btn = document.getElementById('exportCsvBtn');
    const sel = document.getElementById('csvDelimiter');
    if (btn && sel) {
        btn.addEventListener('click', function(e){
            e.preventDefault();
            const d = encodeURIComponent(sel.value);
            window.location.href = '{{ route('admin.progress.export') }}' + '?delimiter=' + d;
        });
    }
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchInfo = document.getElementById('searchInfo');
    const searchResultCount = document.getElementById('searchResultCount');
    const progressTable = document.getElementById('progressTable');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const allRows = document.querySelectorAll('.progress-row');
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim().toLowerCase();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Show search info only if search term is 5+ characters
        if (searchTerm.length >= 5) {
            searchTimeout = setTimeout(() => {
                performSearch(searchTerm);
            }, 300); // 300ms delay for better UX
        } else if (searchTerm.length === 0) {
            // Show all rows if search is empty
            showAllRows();
        } else {
            // Hide search info if less than 5 characters
            searchInfo.classList.add('hidden');
            showAllRows();
        }
    });
    
    function performSearch(searchTerm) {
        let visibleCount = 0;
        
        allRows.forEach(row => {
            const sesiNama = row.getAttribute('data-sesi-nama');
            const pesertaNama = row.getAttribute('data-peserta-nama');
            const searchText = row.getAttribute('data-search-text');
            
            // Check if search term matches sesi name or peserta name
            const matches = sesiNama.includes(searchTerm) || 
                           pesertaNama.includes(searchTerm) || 
                           searchText.includes(searchTerm);
            
            if (matches) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update search info
        searchResultCount.textContent = visibleCount;
        searchInfo.classList.remove('hidden');
        
        // Show/hide table and no results message
        if (visibleCount > 0) {
            progressTable.style.display = '';
            noResultsMessage.classList.add('hidden');
        } else {
            progressTable.style.display = 'none';
            noResultsMessage.classList.remove('hidden');
        }
    }
    
    function showAllRows() {
        allRows.forEach(row => {
            row.style.display = '';
        });
        progressTable.style.display = '';
        noResultsMessage.classList.add('hidden');
        searchInfo.classList.add('hidden');
    }
});
</script>
@endsection




