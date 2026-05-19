@extends('admin.layouts.app')

@section('title', 'Review Roleplay - Assessment Center')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Review Roleplay',
    'subtitle' => 'Review catatan roleplay peserta',
    'actions' => '<a href="' . route('admin.dashboard') . '" class="admin-btn-secondary">← Kembali ke Dashboard</a>',
])

<!-- Filter dan Search -->
        <div class="admin-card p-6 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-64">
                    <input type="text" id="searchInput" placeholder="Cari nama peserta..." 
                           class="w-full admin-input">
                </div>
                <div class="flex items-center space-x-4">
                    <select id="statusFilter" class="admin-input">
                        <option value="">Semua Status</option>
                        <option value="belum_mulai">Belum Mulai</option>
                        <option value="sedang_berlangsung">Sedang Berlangsung</option>
                        <option value="selesai">Selesai</option>
                    </select>
                    <button onclick="exportData()" class="admin-btn-primary">
                        Export Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabel Catatan Roleplay -->
        <div class="admin-card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-primary">Daftar Catatan Roleplay</h3>
            </div>
            
            <div class="admin-card-table-inner">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">
                                Peserta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">
                                Assessment
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">
                                Catatan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">
                                Waktu Submit
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="roleplayTableBody">
                        @forelse($catatanList as $catatan)
                        <tr class="hover:bg-neutral">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">
                                                {{ substr($catatan->peserta->nama_lengkap, 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-primary">
                                            {{ $catatan->peserta->nama_lengkap }}
                                        </div>
                                        <div class="text-sm text-tertiary">
                                            {{ $catatan->peserta->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-primary">{{ $catatan->penilaian->nama_penilaian }}</div>
                                <div class="text-sm text-tertiary">{{ ucfirst($catatan->penilaian->jenis) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-primary max-w-xs truncate">
                                    {{ Str::limit($catatan->catatan, 100) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-tertiary">
                                {{ $catatan->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Submitted
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewDetail({{ $catatan->id }})" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-primary">Belum ada catatan roleplay</h3>
                                <p class="mt-1 text-sm text-tertiary">Catatan roleplay akan muncul di sini setelah peserta mengumpulkan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

<!-- Modal Detail Catatan -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-primary">Detail Catatan Roleplay</h3>
                <button onclick="closeDetailModal()" class="text-tertiary hover:text-tertiary">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="detailContent" class="space-y-4">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function viewDetail(catatanId) {
    // Load detail content via AJAX
    fetch(`/admin/review/roleplay/${catatanId}/detail`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('detailContent').innerHTML = data.html;
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            alert('Terjadi kesalahan saat memuat detail');
        });
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function exportData() {
    window.location.href = '{{ route("admin.review.roleplay.export") }}';
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#roleplayTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function(e) {
    const status = e.target.value;
    const rows = document.querySelectorAll('#roleplayTableBody tr');
    
    if (!status) {
        rows.forEach(row => row.style.display = '');
        return;
    }
    
    rows.forEach(row => {
        const statusCell = row.querySelector('td:nth-child(5)');
        if (statusCell && statusCell.textContent.toLowerCase().includes(status)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection
