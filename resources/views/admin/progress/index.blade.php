@extends('admin.layouts.app')

@section('title', 'Daftar Progres Peserta')

@section('content')
<div class="w-full px-1 sm:px-4 lg:px-8 py-2 sm:py-8">
    <h1 class="text-lg sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-6 px-1">Progres Pengerjaan Peserta</h1>

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
                                <option value="{{ $sesi->nama }}">{{ $sesi->nama }}</option>
                            @endforeach
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

    <!-- Tabel Progres Ringkas -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <!-- Export Options - Top Right -->
        <div class="px-1 sm:px-4 py-1 sm:py-3 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-1 sm:gap-2">
                <div class="flex items-center gap-1">
                    <label class="text-xs text-gray-700">Delimiter:</label>
                    <select id="csvDelimiter" class="border-gray-300 rounded text-xs px-1 sm:px-2 py-0.5 sm:py-1 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value=",">Koma (,)</option>
                        <option value=";">Titik koma (;)</option>
                    </select>
                </div>
                <div class="flex gap-1 sm:gap-2">
                    <a id="exportCsvBtn" href="#" class="flex-1 sm:flex-none bg-green-600 text-white px-1 sm:px-2 py-0.5 sm:py-1 rounded text-xs hover:bg-green-700 transition-colors text-center">
                        Export CSV
                    </a>
                    <a id="exportAnswersBtn" href="#" class="flex-1 sm:flex-none bg-blue-600 text-white px-1 sm:px-2 py-0.5 sm:py-1 rounded text-xs hover:bg-blue-700 transition-colors text-center">
                        Download Jawaban
                    </a>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table id="progressTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-1 sm:px-3 py-1 sm:py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-1 sm:px-3 py-1 sm:py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Nama Sesi</th>
                        <th class="px-1 sm:px-3 py-1 sm:py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                        <th class="px-1 sm:px-3 py-1 sm:py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Instansi</th>
                        <th class="px-1 sm:px-3 py-1 sm:py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Jabatan</th>
                        <th class="px-1 sm:px-3 py-1 sm:py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Studi Kasus</th>
                        <th class="px-1 sm:px-3 py-1 sm:py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">In‑Tray</th>
                        <th class="px-1 sm:px-3 py-1 sm:py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Role‑Play</th>
                        <th class="px-1 sm:px-3 py-1 sm:py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">LGD/FGD</th>
                        <th class="px-1 sm:px-3 py-1 sm:py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="progressTableBody" class="bg-white divide-y divide-gray-200 text-sm">
                    <!-- Data akan dimuat via AJAX -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-1 sm:px-3 py-1 sm:py-2 flex items-center justify-between border-t border-gray-200">
            <div class="flex-1 flex justify-between sm:hidden">
                <button id="prevPageMobile" class="relative inline-flex items-center px-1 sm:px-2 py-1 sm:py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Sebelumnya
                </button>
                <button id="nextPageMobile" class="ml-1 sm:ml-2 relative inline-flex items-center px-1 sm:px-2 py-1 sm:py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Selanjutnya
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs text-gray-700">
                        Menampilkan
                        <span id="showingFrom" class="font-medium">1</span>
                        sampai
                        <span id="showingTo" class="font-medium">10</span>
                        dari
                        <span id="totalRecords" class="font-medium">0</span>
                        hasil
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded shadow-sm -space-x-px" aria-label="Pagination">
                        <button id="prevPage" class="relative inline-flex items-center px-1.5 py-1.5 rounded-l border border-gray-300 bg-white text-xs font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="sr-only">Previous</span>
                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="paginationNumbers" class="flex">
                            <!-- Pagination numbers will be generated here -->
                        </div>
                        <button id="nextPage" class="relative inline-flex items-center px-1.5 py-1.5 rounded-r border border-gray-300 bg-white text-xs font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="sr-only">Next</span>
                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
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
    // Elements
    const exportCsvBtn = document.getElementById('exportCsvBtn');
    const exportAnswersBtn = document.getElementById('exportAnswersBtn');
    const csvDelimiter = document.getElementById('csvDelimiter');
    const progressTable = document.getElementById('progressTable');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const universalSearch = document.getElementById('universalSearch');
    const sessionFilter = document.getElementById('sessionFilter');
    const participantNameFilter = document.getElementById('participantNameFilter');
    const institutionFilter = document.getElementById('institutionFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    
    // Pagination elements
    const prevPage = document.getElementById('prevPage');
    const nextPage = document.getElementById('nextPage');
    const prevPageMobile = document.getElementById('prevPageMobile');
    const nextPageMobile = document.getElementById('nextPageMobile');
    const paginationNumbers = document.getElementById('paginationNumbers');
    const showingFrom = document.getElementById('showingFrom');
    const showingTo = document.getElementById('showingTo');
    const totalRecords = document.getElementById('totalRecords');
    
    let currentPage = 1;
    let totalPages = 1;
    let perPage = 10;
    let currentFilters = {
        universalSearch: '',
        session: '',
        assessmentType: '',
        participantName: '',
        institution: ''
    };
    
    // Load initial data
    loadData();
    
    // Export CSV functionality
    if (exportCsvBtn && csvDelimiter) {
        exportCsvBtn.addEventListener('click', function(e){
            e.preventDefault();
            const delimiter = encodeURIComponent(csvDelimiter.value);
            const params = buildFilterParams();
            window.location.href = '{{ route("admin.progress.export") }}' + '?delimiter=' + delimiter + params;
        });
    }
    
    // Export Answers functionality
    if (exportAnswersBtn) {
        exportAnswersBtn.addEventListener('click', function(e){
            e.preventDefault();
            const params = buildFilterParams();
            window.location.href = '{{ route("admin.progress.export-answers") }}' + params;
        });
    }
    
    // Universal search functionality
    if (universalSearch) {
    let searchTimeout;
        universalSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentFilters.universalSearch = this.value.trim();
                currentPage = 1;
                loadData();
            }, 500); // 500ms delay for better UX
        });
    }
    
    // Apply filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            currentFilters.universalSearch = universalSearch.value.trim();
            currentFilters.session = sessionFilter.value;
            currentFilters.participantName = participantNameFilter.value;
            currentFilters.institution = institutionFilter.value;
            currentPage = 1;
            loadData();
        });
    }
    
    // Reset filters
    if (resetFilters) {
        resetFilters.addEventListener('click', function() {
            universalSearch.value = '';
            sessionFilter.value = '';
            participantNameFilter.value = '';
            institutionFilter.value = '';
            currentFilters = { universalSearch: '', session: '', assessmentType: '', participantName: '', institution: '' };
            currentPage = 1;
            loadData();
        });
    }
    
    // Pagination event listeners
    if (prevPage) {
        prevPage.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                loadData();
            }
        });
    }
    
    if (nextPage) {
        nextPage.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                loadData();
            }
        });
    }
    
    if (prevPageMobile) {
        prevPageMobile.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                loadData();
            }
        });
    }
    
    if (nextPageMobile) {
        nextPageMobile.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                loadData();
            }
        });
    }
    
    function buildFilterParams() {
        let params = '';
        if (currentFilters.universalSearch) params += '&universal_search=' + encodeURIComponent(currentFilters.universalSearch);
        if (currentFilters.session) params += '&session=' + encodeURIComponent(currentFilters.session);
        if (currentFilters.participantName) params += '&participant_name=' + encodeURIComponent(currentFilters.participantName);
        if (currentFilters.institution) params += '&institution=' + encodeURIComponent(currentFilters.institution);
        return params;
    }
    
    function loadData() {
        const params = new URLSearchParams({
            page: currentPage,
            per_page: perPage,
            universal_search: currentFilters.universalSearch,
            session: currentFilters.session,
            assessment_type: currentFilters.assessmentType,
            participant_name: currentFilters.participantName,
            institution: currentFilters.institution
        });
        
        fetch('{{ route("admin.progress.data") }}?' + params.toString())
            .then(response => response.json())
            .then(data => {
                updateTable(data.data);
                updatePagination(data);
            })
            .catch(error => {
                console.error('Error loading data:', error);
                document.getElementById('progressTableBody').innerHTML = '<tr><td colspan="10" class="px-4 py-2 text-center text-red-500">Error loading data</td></tr>';
            });
    }
    
    function updateTable(data) {
        const tbody = document.getElementById('progressTableBody');
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="10" class="px-4 py-2 text-center text-gray-500">Tidak ada data ditemukan</td></tr>';
            progressTable.style.display = '';
            noResultsMessage.classList.add('hidden');
            return;
        }
        
        let html = '';
        data.forEach((item, index) => {
            const rowNumber = (currentPage - 1) * perPage + index + 1;
            html += `
                <tr class="progress-row">
                    <td class="px-1 sm:px-3 py-1 sm:py-2">${rowNumber}</td>
                    <td class="px-1 sm:px-3 py-1 sm:py-2 hidden sm:table-cell">
                        <span class="font-medium text-gray-900">${item.sesi_nama}</span>
                    </td>
                    <td class="px-1 sm:px-3 py-1 sm:py-2">
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 text-xs sm:text-sm">${item.peserta_nama}</span>
                            <span class="text-xs text-gray-500 sm:hidden">${item.sesi_nama}</span>
                            <span class="text-xs text-gray-500">${item.peserta_email || '-'}</span>
                        </div>
                    </td>
                    <td class="px-1 sm:px-3 py-1 sm:py-2 hidden md:table-cell">${item.peserta_instansi || '-'}</td>
                    <td class="px-1 sm:px-3 py-1 sm:py-2 hidden lg:table-cell">${item.peserta_jabatan || '-'}</td>
                    <td class="px-1 sm:px-3 py-1 sm:py-2 hidden sm:table-cell">${item.studi_kasus_status}</td>
                    <td class="px-1 sm:px-3 py-1 sm:py-2 hidden md:table-cell">${item.in_tray_status}</td>
                    <td class="px-1 sm:px-3 py-1 sm:py-2 hidden md:table-cell">${item.roleplay_status}</td>
                    <td class="px-1 sm:px-3 py-1 sm:py-2 hidden lg:table-cell">${item.fgd_status}</td>
                    <td class="px-1 sm:px-3 py-1 sm:py-2">
                        <div class="flex flex-col gap-0.5 sm:gap-1">
                            <button data-action="view-answers" data-peserta-id="${item.peserta_id}" data-sesi-id="${item.sesi_id}" 
                                    class="text-xs bg-blue-100 text-blue-800 px-1 sm:px-1.5 py-0.5 rounded hover:bg-blue-200 transition-colors">
                                Lihat Jawaban
                            </button>
                            <button data-action="download-data" data-peserta-id="${item.peserta_id}" data-sesi-id="${item.sesi_id}" 
                                    class="text-xs bg-green-100 text-green-800 px-1 sm:px-1.5 py-0.5 rounded hover:bg-green-200 transition-colors">
                                Download
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        progressTable.style.display = '';
        noResultsMessage.classList.add('hidden');
    }
    
    function updatePagination(data) {
        totalPages = data.last_page;
        totalRecords.textContent = data.total;
        showingFrom.textContent = data.from || 0;
        showingTo.textContent = data.to || 0;
        
        // Update pagination buttons
        prevPage.disabled = currentPage <= 1;
        nextPage.disabled = currentPage >= totalPages;
        prevPageMobile.disabled = currentPage <= 1;
        nextPageMobile.disabled = currentPage >= totalPages;
        
        // Generate pagination numbers
        let paginationHtml = '';
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === currentPage;
            paginationHtml += `
                <button onclick="goToPage(${i})" 
                        class="relative inline-flex items-center px-1.5 sm:px-2 py-1 sm:py-1.5 border text-xs font-medium ${isActive ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'}">
                    ${i}
                </button>
            `;
        }
        
        paginationNumbers.innerHTML = paginationHtml;
    }
    
    // Global function for pagination
    window.goToPage = function(page) {
        currentPage = page;
        loadData();
    };
    
    // Event listeners for action buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-action="view-answers"]')) {
            e.preventDefault();
            const pesertaId = e.target.getAttribute('data-peserta-id');
            const sesiId = e.target.getAttribute('data-sesi-id');
            console.log('View answers clicked:', pesertaId, sesiId);
            window.location.href = '{{ route("admin.progress.answers") }}?peserta_id=' + pesertaId + '&sesi_id=' + sesiId;
        }
        
        if (e.target.matches('[data-action="download-data"]')) {
            e.preventDefault();
            const pesertaId = e.target.getAttribute('data-peserta-id');
            const sesiId = e.target.getAttribute('data-sesi-id');
            console.log('Download data clicked:', pesertaId, sesiId);
            window.location.href = '{{ route("admin.progress.export-answers") }}?peserta_id=' + pesertaId + '&sesi_id=' + sesiId;
        }
    });
});
</script>
@endsection




