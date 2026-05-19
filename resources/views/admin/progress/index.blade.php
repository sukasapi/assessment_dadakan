@extends('admin.layouts.app')

@section('title', 'Daftar Progres Peserta')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Progres Pengerjaan Peserta',
    'subtitle' => 'Monitor progress assessment peserta',
])

    <!-- Filter Card -->
    <div class="mb-2 sm:mb-4">
        <div class="admin-card p-2 sm:p-4">
            <!-- Filter Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 mb-2 sm:mb-3">
                <!-- Left Column -->
                <div class="space-y-2 sm:space-y-3">
                    <!-- Filter Sesi -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-primary mb-1">Nama Sesi</label>
                        <select id="sessionFilter" class="w-full px-2 sm:px-3 py-1.5 sm:py-2 admin-input text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                        <label class="block text-xs sm:text-sm font-medium text-primary mb-1">Nama Peserta</label>
                        <input type="text" 
                               id="participantNameFilter" 
                               placeholder="Masukkan nama peserta..."
                               class="w-full px-2 sm:px-3 py-1.5 sm:py-2 admin-input text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                <button type="button" id="resetFilters" class="w-full sm:w-auto px-2 sm:px-3 py-1 sm:py-1.5 admin-input text-xs sm:text-sm font-medium text-primary bg-white hover:bg-neutral focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Reset
                </button>
                <button type="button" id="applyFilters" class="w-full sm:w-auto px-2 sm:px-3 py-1 sm:py-1.5 border border-transparent rounded-md text-xs sm:text-sm font-medium text-white admin-btn-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Search
                </button>
            </div>
        </div>
    </div>

    <!-- Tabel Progres Ringkas -->
    <div class="admin-card">
        <!-- Export Options - Top Right -->
        <div class="admin-card-table-toolbar">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-1 sm:gap-2">
                <div class="flex items-center gap-1">
                    <label class="text-xs text-primary">Delimiter:</label>
                    <select id="csvDelimiter" class="border-gray-300 rounded text-xs px-1 sm:px-2 py-0.5 sm:py-1 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value=",">Koma (,)</option>
                        <option value=";">Titik koma (;)</option>
                    </select>
                </div>
                <div class="flex gap-1 sm:gap-2">
                    <a id="exportCsvBtn" href="#" class="flex-1 sm:flex-none admin-btn-secondary px-1 sm:px-2 py-0.5 sm:py-1 text-xs text-center">
                        Export CSV
                    </a>
                    <a id="exportAnswersBtn" href="#" class="flex-1 sm:flex-none admin-btn-primary px-1 sm:px-2 py-0.5 sm:py-1 text-xs text-center">
                        Download Jawaban
                    </a>
                </div>
            </div>
        </div>
        
        <div class="admin-card-table-inner">
            <table id="progressTable" class="admin-table w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="hidden sm:table-cell">Nama Sesi</th>
                        <th>Nama Peserta</th>
                        <th class="hidden md:table-cell">Instansi</th>
                        <th class="hidden lg:table-cell">Jabatan</th>
                        <th class="hidden sm:table-cell">Studi Kasus</th>
                        <th class="hidden md:table-cell">In‑Tray</th>
                        <th class="hidden md:table-cell">Role‑Play</th>
                        <th class="hidden lg:table-cell">LGD/FGD</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="progressTableBody" class="text-sm">
                    <!-- Data akan dimuat via AJAX -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="admin-card-table-footer flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                <button id="prevPageMobile" class="relative inline-flex items-center px-1 sm:px-2 py-1 sm:py-1.5 border border-gray-300 text-xs font-medium rounded text-primary bg-white hover:bg-neutral disabled:opacity-50 disabled:cursor-not-allowed">
                    Sebelumnya
                </button>
                <button id="nextPageMobile" class="ml-1 sm:ml-2 relative inline-flex items-center px-1 sm:px-2 py-1 sm:py-1.5 border border-gray-300 text-xs font-medium rounded text-primary bg-white hover:bg-neutral disabled:opacity-50 disabled:cursor-not-allowed">
                    Selanjutnya
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs text-primary">
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
                        <button id="prevPage" class="relative inline-flex items-center px-1.5 py-1.5 rounded-l border border-gray-300 bg-white text-xs font-medium text-tertiary hover:bg-neutral disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="sr-only">Previous</span>
                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="paginationNumbers" class="flex">
                            <!-- Pagination numbers will be generated here -->
                        </div>
                        <button id="nextPage" class="relative inline-flex items-center px-1.5 py-1.5 rounded-r border border-gray-300 bg-white text-xs font-medium text-tertiary hover:bg-neutral disabled:opacity-50 disabled:cursor-not-allowed">
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
    <div id="noResultsMessage" class="hidden text-center py-8 bg-neutral rounded-lg border-2 border-dashed border-[#E2E8F0]">
        <div class="text-tertiary text-lg font-medium mb-2">Tidak ada data ditemukan</div>
        <div class="text-tertiary text-sm opacity-75">Coba gunakan kata kunci pencarian yang berbeda</div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Elements
    const exportCsvBtn = document.getElementById('exportCsvBtn');
    const exportAnswersBtn = document.getElementById('exportAnswersBtn');
    const csvDelimiter = document.getElementById('csvDelimiter');
    const progressTable = document.getElementById('progressTable');
    const noResultsMessage = document.getElementById('noResultsMessage');
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
    
    // Apply filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (sessionFilter) currentFilters.session = sessionFilter.value;
            if (participantNameFilter) currentFilters.participantName = participantNameFilter.value.trim();
            if (institutionFilter) currentFilters.institution = institutionFilter.value.trim();
            currentPage = 1;
            loadData();
        });
    }
    
    // Reset filters
    if (resetFilters) {
        resetFilters.addEventListener('click', function() {
            sessionFilter.value = '';
            participantNameFilter.value = '';
            institutionFilter.value = '';
            currentFilters = { session: '', assessmentType: '', participantName: '', institution: '' };
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
        if (currentFilters.session) params += '&session=' + encodeURIComponent(currentFilters.session);
        if (currentFilters.participantName) params += '&participant_name=' + encodeURIComponent(currentFilters.participantName);
        if (currentFilters.institution) params += '&institution=' + encodeURIComponent(currentFilters.institution);
        return params;
    }
    
    function loadData() {
        const params = new URLSearchParams({
            page: currentPage,
            per_page: perPage,
            session: currentFilters.session || '',
            assessment_type: currentFilters.assessmentType || '',
            participant_name: currentFilters.participantName || '',
            institution: currentFilters.institution || ''
        });
        
        fetch('{{ route("admin.progress.data") }}?' + params.toString())
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                updateTable(data.data);
                updatePagination(data);
            })
            .catch(error => {
                // Silent fail
                const tbody = document.getElementById('progressTableBody');
                if (tbody) {
                    tbody.innerHTML = '<tr><td colspan="10" class="px-4 py-2 text-center text-red-500">Error loading data: ' + error.message + '</td></tr>';
                }
            });
    }
    
    function renderStatusSelect(penilaianId, statusValue, pesertaId, sesiId) {
        if (!penilaianId) return '<span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-tertiary border">tidak tersedia</span>';
        const status = statusValue || 'belum_mulai';
        const opts = [
            ['belum_mulai', 'Belum Mulai'],
            ['sedang_berlangsung', 'Sedang Berlangsung'],
            ['selesai', 'Selesai'],
            ['dibatalkan', 'Dibatalkan']
        ];
        let options = opts.map(([val, label]) => 
            '<option value="' + val + '"' + (status === val ? ' selected' : '') + '>' + label + '</option>'
        ).join('');
        return '<select class="status-select px-2 py-0.5 rounded text-xs border border-gray-300 focus:ring-1 focus:ring-blue-500 w-full max-w-[140px]" ' +
            'data-peserta-id="' + pesertaId + '" data-sesi-id="' + sesiId + '" data-penilaian-id="' + penilaianId + '">' + options + '</select>';
    }

    function updateTable(data) {
        const tbody = document.getElementById('progressTableBody');
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="10" class="px-4 py-2 text-center text-tertiary">Tidak ada data ditemukan</td></tr>';
            progressTable.style.display = '';
            noResultsMessage.classList.add('hidden');
            return;
        }
        
        let html = '';
        data.forEach((item, index) => {
            const rowNumber = (currentPage - 1) * perPage + index + 1;
            const stCell = renderStatusSelect(item.studi_kasus_penilaian_id, item.studi_kasus_status_value, item.peserta_id, item.sesi_id);
            const itCell = renderStatusSelect(item.in_tray_penilaian_id, item.in_tray_status_value, item.peserta_id, item.sesi_id) + (item.in_tray_model_type ? ' <span class="text-xs text-tertiary">' + (item.in_tray_model_type === 'prioritas' ? '(Prioritas)' : '(Urutan)') + '</span>' : '');
            const rpCell = renderStatusSelect(item.roleplay_penilaian_id, item.roleplay_status_value, item.peserta_id, item.sesi_id);
            const fgCell = renderStatusSelect(item.fgd_penilaian_id, item.fgd_status_value, item.peserta_id, item.sesi_id);
            html += `
                <tr class="progress-row">
                    <td>${rowNumber}</td>
                    <td class="hidden sm:table-cell">
                        <span class="font-medium text-primary">${item.sesi_nama}</span>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="font-medium text-primary text-xs sm:text-sm">${item.peserta_nama}</span>
                            <span class="text-xs text-tertiary sm:hidden">${item.sesi_nama}</span>
                            <span class="text-xs text-tertiary">${item.peserta_email || '-'}</span>
                        </div>
                    </td>
                    <td class="hidden md:table-cell">${item.peserta_instansi || '-'}</td>
                    <td class="hidden lg:table-cell">${item.peserta_jabatan || '-'}</td>
                    <td class="hidden sm:table-cell">${stCell}</td>
                    <td class="hidden md:table-cell"><div class="flex flex-col gap-0.5">${itCell}</div></td>
                    <td class="hidden md:table-cell">${rpCell}</td>
                    <td class="hidden lg:table-cell">${fgCell}</td>
                    <td>
                        <div class="flex flex-col gap-0.5 sm:gap-1">
                            <button data-action="view-answers" data-peserta-id="${item.peserta_id}" data-sesi-id="${item.sesi_id}" 
                                    class="text-xs bg-blue-100 text-blue-800 px-1 sm:px-1.5 py-0.5 rounded hover:bg-blue-200 transition-colors">
                                Lihat Jawaban
                            </button>
                            ${item.show_matrix ? `
                            <button data-action="view-matrix" data-peserta-id="${item.peserta_id}" data-sesi-id="${item.sesi_id}" 
                                    class="text-xs bg-purple-100 text-purple-800 px-1 sm:px-1.5 py-0.5 rounded hover:bg-purple-200 transition-colors">
                                Matriks In-Tray
                            </button>
                            ` : ''}
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
                        class="relative inline-flex items-center px-1.5 sm:px-2 py-1 sm:py-1.5 border text-xs font-medium ${isActive ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-tertiary hover:bg-neutral'}">
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
    
    // Event listener for status dropdown change
    adminDelegateChange('.status-select', function (select) {
            const pesertaId = select.getAttribute('data-peserta-id');
            const sesiId = select.getAttribute('data-sesi-id');
            const penilaianId = select.getAttribute('data-penilaian-id');
            const status = select.value;
            const url = '{{ route("admin.progress.update-status-by-keys") }}';
            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    peserta_id: parseInt(pesertaId),
                    penilaian_id: parseInt(penilaianId),
                    sesi_penilaian_id: parseInt(sesiId),
                    status: status
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Status berhasil diupdate', 'success');
                    loadData();
                } else {
                    showNotification(data.message || 'Gagal mengupdate status', 'error');
                }
            })
            .catch(err => {
                showNotification('Terjadi kesalahan saat mengupdate status', 'error');
            });
    });

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-xl transform transition-all duration-300 ease-in-out translate-x-full opacity-0 ' +
            (type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' : 'bg-red-500 text-white border-l-4 border-red-600');
        const icon = type === 'success'
            ? '<svg class="w-5 h-5 mr-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
            : '<svg class="w-5 h-5 mr-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
        notification.innerHTML = icon + message;
        document.body.appendChild(notification);
        setTimeout(() => notification.classList.add('translate-x-0', 'opacity-100'), 100);
        setTimeout(() => {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    // Tombol aksi di baris tabel (delegasi — klik teks di dalam tombol tetap jalan)
    adminDelegateClick('[data-action="view-answers"]', function (btn, e) {
        e.preventDefault();
        const pesertaId = btn.getAttribute('data-peserta-id');
        const sesiId = btn.getAttribute('data-sesi-id');
        window.location.href = '{{ route("admin.progress.answers") }}?peserta_id=' + pesertaId + '&sesi_id=' + sesiId;
    });

    adminDelegateClick('[data-action="view-matrix"]', function (btn, e) {
        e.preventDefault();
        const pesertaId = btn.getAttribute('data-peserta-id');
        const sesiId = btn.getAttribute('data-sesi-id');
        window.location.href = '{{ route("admin.intray-matrix.show", ["sesiId" => ":sesiId", "pesertaId" => ":pesertaId"]) }}'.replace(':sesiId', sesiId).replace(':pesertaId', pesertaId);
    });

    adminDelegateClick('[data-action="download-data"]', function (btn, e) {
        e.preventDefault();
        const pesertaId = btn.getAttribute('data-peserta-id');
        const sesiId = btn.getAttribute('data-sesi-id');
        window.location.href = '{{ route("admin.progress.export-answers") }}?peserta_id=' + pesertaId + '&sesi_id=' + sesiId;
    });
});
</script>
@endsection




