@extends('admin.layouts.app')

@section('title', 'Manajemen Peserta')

@section('content')
<style>
    @media (max-width: 1023px) {
        .peserta-card dt { font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; color: #64748B; }
        .peserta-card dd { font-size: 0.875rem; color: #0F172A; word-break: break-word; }
    }
</style>
<div class="admin-page-header mb-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-start w-full">
            <div>
                <h1 class="admin-page-title">Manajemen Peserta</h1>
                <p class="admin-page-subtitle">Kelola data peserta assessment</p>
            </div>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')"
                        class="admin-btn-outline flex-1 sm:flex-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter
                </button>
                <button type="button" id="btnExportPeserta"
                        class="admin-btn-outline flex-1 sm:flex-none text-emerald-700 border-emerald-300 hover:bg-emerald-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" 
                        class="admin-btn-secondary flex-1 sm:flex-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Import CSV
                </button>
            </div>
        </div>
</div>

        <!-- Panel Filter -->
        <div id="filterPanel" class="mb-6 admin-card p-4 {{ request()->hasAny(['search', 'instansi', 'jenis_kelamin', 'aktif', 'tanggal_dari', 'tanggal_sampai']) ? '' : 'hidden' }}">
            <form method="GET" action="{{ route('admin.peserta.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="md:col-span-2 lg:col-span-3">
                        <label for="search" class="block text-sm font-medium text-primary mb-1">Cari Peserta</label>
                        <input type="text" name="search" id="search" value="{{ $filters['search'] ?? '' }}"
                               placeholder="Nama, email, PIN, atau instansi..."
                               class="w-full px-3 py-2 admin-input text-sm">
                    </div>
                    <div>
                        <label for="filter_instansi" class="block text-sm font-medium text-primary mb-1">Instansi</label>
                        <select name="instansi" id="filter_instansi"
                                class="w-full px-3 py-2 admin-input text-sm">
                            <option value="">Semua Instansi</option>
                            @foreach($instansiList as $inst)
                                <option value="{{ $inst }}" {{ ($filters['instansi'] ?? '') === $inst ? 'selected' : '' }}>{{ $inst }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filter_jenis_kelamin" class="block text-sm font-medium text-primary mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="filter_jenis_kelamin"
                                class="w-full px-3 py-2 admin-input text-sm">
                            <option value="">Semua</option>
                            <option value="L" {{ ($filters['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ ($filters['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label for="filter_aktif" class="block text-sm font-medium text-primary mb-1">Status</label>
                        <select name="aktif" id="filter_aktif"
                                class="w-full px-3 py-2 admin-input text-sm">
                            <option value="">Semua Status</option>
                            <option value="1" {{ ($filters['aktif'] ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ ($filters['aktif'] ?? '') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_dari" class="block text-sm font-medium text-primary mb-1">Tanggal Daftar Dari</label>
                        <input type="date" name="tanggal_dari" id="tanggal_dari" value="{{ $filters['tanggal_dari'] ?? '' }}"
                               class="w-full px-3 py-2 admin-input text-sm">
                    </div>
                    <div>
                        <label for="tanggal_sampai" class="block text-sm font-medium text-primary mb-1">Tanggal Daftar Sampai</label>
                        <input type="date" name="tanggal_sampai" id="tanggal_sampai" value="{{ $filters['tanggal_sampai'] ?? '' }}"
                               class="w-full px-3 py-2 admin-input text-sm">
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:justify-between sm:items-center gap-3 mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-tertiary">
                        <label for="export_delimiter">Delimiter export:</label>
                        <select id="export_delimiter" class="px-2 py-1 border border-gray-300 rounded text-sm">
                            <option value=",">Koma (,)</option>
                            <option value=";">Semicolon (;)</option>
                        </select>
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <a href="{{ route('admin.peserta.index') }}"
                           class="flex-1 sm:flex-none text-center px-4 py-2 admin-input text-sm text-primary hover:bg-neutral">
                            Reset
                        </a>
                        <button type="submit"
                                class="flex-1 sm:flex-none admin-btn-secondary text-sm">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
        <div class="mb-6 admin-alert admin-alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('imported_count'))
        <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Berhasil mengimport {{ session('imported_count') }} peserta!
            </div>
            @if(session('skipped_count'))
                <div class="mt-2 text-sm">
                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    Data yang dilewati: {{ session('skipped_count') }} peserta
                </div>
            @endif
        </div>
        @endif

        @if($errors->has('import_errors'))
        <div class="mb-6 admin-alert admin-alert-error">
            <h4 class="font-bold mb-2">Error saat import:</h4>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->get('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($errors->has('error'))
        <div class="mb-6 admin-alert admin-alert-error">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ $errors->first('error') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 admin-alert admin-alert-error">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h4 class="font-medium">Error saat import:</h4>
                    <p class="mt-1">{{ session('error') }}</p>
                    <div class="mt-2 text-sm">
                        <p class="font-medium">Tips troubleshooting:</p>
                        <ul class="list-disc list-inside mt-1 space-y-1">
                            <li>Pastikan file CSV tidak kosong dan memiliki header yang benar</li>
                            <li>Format tanggal harus YYYY-MM-DD (contoh: 1990-01-01)</li>
                            <li>Email dan PIN harus unik (tidak boleh sama dengan peserta lain)</li>
                            <li>Jenis kelamin harus L atau P</li>
                            <li>Ukuran file maksimal 2MB</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(session('import_errors'))
        <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h4 class="font-medium">Import selesai dengan beberapa error:</h4>
                    <ul class="mt-2 text-sm space-y-1">
                        @foreach(session('import_errors') as $error)
                            <li class="flex items-start">
                                <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                    
                    @if(session('csv_header'))
                    <div class="mt-3 p-3 bg-orange-50 border border-orange-200 rounded">
                        <h5 class="font-medium text-orange-800 text-sm mb-2">Header CSV yang dibaca (setelah dibersihkan):</h5>
                        <div class="text-xs text-orange-700 font-mono bg-orange-100 p-2 rounded">
                            {{ implode(' | ', session('csv_header')) }}
                        </div>
                        
                        @if(session('csv_header_original'))
                        <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded">
                            <h6 class="font-medium text-red-800 text-xs mb-1">Header asli (sebelum dibersihkan):</h6>
                            <div class="text-xs text-red-700 font-mono bg-red-100 p-2 rounded">
                                {{ implode(' | ', session('csv_header_original')) }}
                            </div>
                            <p class="text-xs text-red-600 mt-1">
                                <strong>⚠️ Masalah:</strong> Header asli mengandung karakter BOM atau spasi yang menyebabkan mapping kolom gagal.
                            </p>
                            <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                                <p class="text-xs text-green-700">
                                    <strong>✅ Otomatis Dibersihkan:</strong> File CSV dengan BOM telah otomatis dibersihkan dan data berhasil diproses.
                                </p>
                            </div>
                        </div>
                        @endif
                        
                        <p class="text-xs text-orange-600 mt-2">
                            <strong>Tips:</strong> Pastikan header CSV sesuai dengan template yang disediakan. 
                            Header yang tidak sesuai akan menyebabkan data tidak terbaca dengan benar.
                        </p>
                        
                        <div class="mt-3 p-2 bg-blue-50 border border-blue-200 rounded">
                            <h6 class="font-medium text-blue-800 text-xs mb-1">💡 Cara Membuat CSV yang Bersih:</h6>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• Gunakan <strong>Notepad++</strong> atau <strong>VS Code</strong> untuk edit CSV</li>
                                <li>• Pastikan encoding file adalah <strong>UTF-8 (tanpa BOM)</strong></li>
                                <li>• Jangan ada spasi ekstra di awal/akhir header</li>
                                <li>• Gunakan koma (,) sebagai separator, bukan titik koma (;)</li>
                                <li>• Download template resmi dari link di atas</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3 p-2 bg-purple-50 border border-purple-200 rounded">
                            <h6 class="font-medium text-purple-800 text-xs mb-1">🔄 Auto-Cleanup:</h6>
                            <ul class="text-xs text-purple-700 space-y-1">
                                <li>• File temporary otomatis dihapus setelah import selesai</li>
                                <li>• Session data dibersihkan untuk keamanan</li>
                                <li>• BOM dan karakter khusus otomatis dibersihkan</li>
                                <li>• Baris kosong otomatis dilewati</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3 p-2 bg-red-50 border border-red-200 rounded">
                            <h6 class="font-medium text-red-800 text-xs mb-1">⚠️ Troubleshooting "Unexpected data found":</h6>
                            <ul class="text-xs text-red-700 space-y-1">
                                <li>• Pastikan tidak ada baris kosong di akhir file CSV</li>
                                <li>• Pastikan tidak ada spasi ekstra setelah data terakhir</li>
                                <li>• Gunakan file CSV yang bersih tanpa karakter tersembunyi</li>
                                <li>• Check log Laravel untuk detail error yang lebih lengkap</li>
                                <li>• Format tanggal harus YYYY-MM-DD, DD/MM/YYYY, atau DD-MM-YYYY</li>
                                <li>• Pastikan semua kolom wajib terisi (Nama, Email, Tanggal Lahir, Jenis Kelamin, PIN)</li>
                            </ul>
                        </div>
                        
                        <div class="mt-3 p-2 bg-green-50 border border-green-200 rounded">
                            <h6 class="font-medium text-green-800 text-xs mb-1">✅ File Test yang Sudah Diperbaiki:</h6>
                            <ul class="text-xs text-green-700 space-y-1">
                                <li>• <a href="/test_fixed.csv" class="underline" download>Download test_fixed.csv</a> - File test yang bersih</li>
                                <li>• Format tanggal: YYYY-MM-DD (contoh: 1990-01-01)</li>
                                <li>• Tidak ada baris kosong di akhir</li>
                                <li>• Encoding UTF-8 tanpa BOM</li>
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Peserta Table -->
        <div class="admin-card overflow-hidden">
            <div class="admin-card-table-header">
                <h3 class="text-lg font-semibold text-primary">Daftar Peserta</h3>
                <p class="text-sm text-tertiary mt-1">
                    Total {{ $pesertaList->total() }} peserta
                    @if(request()->hasAny(['search', 'instansi', 'jenis_kelamin', 'aktif', 'tanggal_dari', 'tanggal_sampai']))
                        <span class="text-secondary">(hasil filter)</span>
                    @endif
                </p>
            </div>
            
            @if($pesertaList->total() > 0)
            <div class="admin-card-table-body">
            {{-- Tampilan kartu: mobile & tablet --}}
            <div class="lg:hidden space-y-4 pb-2">
                @foreach($pesertaList as $peserta)
                @php
                    $totalAssessment = $peserta->kemajuanPenilaian->count();
                    $completedAssessment = $peserta->kemajuanPenilaian->where('status', 'selesai')->count();
                    $progressPercentage = $totalAssessment > 0 ? round(($completedAssessment / $totalAssessment) * 100) : 0;
                    $sesiTerdaftar = $peserta->getNamaSesiAssessmentTerdaftar();
                    $terdaftarSesi = $sesiTerdaftar->isNotEmpty();
                @endphp
                <article class="peserta-card border border-gray-200 rounded-lg p-4 bg-gray-50/50 hover:bg-neutral transition-colors">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0 flex-1">
                            <h4 class="font-semibold text-primary text-sm sm:text-base break-words">{{ $peserta->nama_lengkap }}</h4>
                            <p class="text-xs text-tertiary mt-0.5">PIN: {{ $peserta->pin }}</p>
                        </div>
                        @include('admin.peserta.partials.aksi-buttons', ['peserta' => $peserta, 'terdaftarSesi' => $terdaftarSesi, 'sesiTerdaftar' => $sesiTerdaftar])
                    </div>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2.5">
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $peserta->email ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt>Telepon</dt>
                            <dd>{{ $peserta->nomor_telepon ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt>Instansi</dt>
                            <dd>{{ $peserta->instansi ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt>Jabatan</dt>
                            <dd>{{ $peserta->jabatan_saat_ini ?: '-' }}@if($peserta->grade) <span class="text-tertiary">· {{ $peserta->grade }}</span>@endif</dd>
                        </div>
                        <div>
                            <dt>Progres</dt>
                            <dd>
                                <span class="font-medium">{{ $completedAssessment }}/{{ $totalAssessment }}</span>
                                <span class="text-tertiary">({{ $progressPercentage }}%)</span>
                                <div class="admin-progress-track h-1.5 mt-1 max-w-xs">
                                    <div class="admin-progress-fill h-1.5" style="width: {{ $progressPercentage }}%"></div>
                                </div>
                            </dd>
                        </div>
                        <div>
                            <dt>Tanggal Daftar</dt>
                            <dd>{{ $peserta->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </article>
                @endforeach
            </div>

            {{-- Tampilan tabel: desktop --}}
            <div class="hidden lg:block peserta-table-wrap">
                <table class="peserta-table">
                    <thead>
                        <tr>
                            <th class="col-nama">Nama</th>
                            <th class="col-email">Email</th>
                            <th class="col-instansi">Instansi</th>
                            <th class="col-jabatan">Jabatan</th>
                            <th class="col-progres">Progres</th>
                            <th class="col-tanggal">Tgl. Daftar</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesertaList as $peserta)
                        @php
                            $totalAssessment = $peserta->kemajuanPenilaian->count();
                            $completedAssessment = $peserta->kemajuanPenilaian->where('status', 'selesai')->count();
                            $progressPercentage = $totalAssessment > 0 ? round(($completedAssessment / $totalAssessment) * 100) : 0;
                            $sesiTerdaftar = $peserta->getNamaSesiAssessmentTerdaftar();
                            $terdaftarSesi = $sesiTerdaftar->isNotEmpty();
                        @endphp
                        <tr>
                            <td class="col-nama">
                                <span class="cell-truncate cell-name" title="{{ $peserta->nama_lengkap }}">{{ $peserta->nama_lengkap }}</span>
                                <span class="cell-truncate cell-sub" title="PIN: {{ $peserta->pin }}">PIN: {{ $peserta->pin }}</span>
                            </td>
                            <td class="col-email">
                                <span class="cell-truncate" title="{{ $peserta->email }}">{{ $peserta->email ?: '-' }}</span>
                                <span class="cell-truncate cell-sub" title="{{ $peserta->nomor_telepon }}">{{ $peserta->nomor_telepon ?: '-' }}</span>
                            </td>
                            <td class="col-instansi">
                                <span class="cell-truncate" title="{{ $peserta->instansi }}">{{ $peserta->instansi ?: '-' }}</span>
                            </td>
                            <td class="col-jabatan">
                                <span class="cell-truncate" title="{{ $peserta->jabatan_saat_ini }}">{{ $peserta->jabatan_saat_ini ?: '-' }}</span>
                                @if($peserta->grade)
                                <span class="cell-truncate cell-sub" title="{{ $peserta->grade }}">{{ $peserta->grade }}</span>
                                @endif
                            </td>
                            <td class="col-progres">
                                <span class="font-medium">{{ $completedAssessment }}/{{ $totalAssessment }}</span>
                                <span class="cell-sub">{{ $progressPercentage }}%</span>
                                <div class="admin-progress-track h-1.5 mt-1">
                                    <div class="admin-progress-fill h-1.5" style="width: {{ $progressPercentage }}%"></div>
                                </div>
                            </td>
                            <td class="col-tanggal">
                                <span>{{ $peserta->created_at->format('d/m/y') }}</span>
                                <span class="cell-sub block">{{ $peserta->created_at->format('H:i') }}</span>
                            </td>
                            <td class="col-aksi">
                                @include('admin.peserta.partials.aksi-buttons', ['peserta' => $peserta, 'terdaftarSesi' => $terdaftarSesi, 'sesiTerdaftar' => $sesiTerdaftar])
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>

            <div class="admin-card-table-footer">
                @include('admin.partials.pagination', ['paginator' => $pesertaList, 'label' => 'peserta'])
            </div>
            @else
            <div class="px-6 py-12 text-center">
                <div class="text-tertiary mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-primary mb-2">Belum ada peserta</h3>
                <p class="text-tertiary mb-4">Mulai dengan mengimport peserta dari file CSV atau tambah peserta secara manual.</p>
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" 
                        class="admin-btn-primary">
                    Import CSV Pertama
                </button>
            </div>
            @endif
        </div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 p-4">
    <div class="relative top-4 sm:top-12 mx-auto p-4 sm:p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-primary">Import Peserta dari CSV</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-tertiary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="mb-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <a href="/template_import_peserta.csv" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium underline"
                       download="template_import_peserta.csv">
                        Download Template CSV
                    </a>
                </div>
                <p class="text-xs text-tertiary">Template ini sudah berisi contoh data yang bisa langsung digunakan</p>
            </div>
            
            <form action="{{ route('admin.peserta.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="mb-4">
                    <label for="delimiter" class="block text-sm font-medium text-primary mb-2">
                        Delimiter CSV
                    </label>
                    <select name="delimiter"
                            id="delimiter"
                            class="admin-input focus:border-transparent text-sm"
                            onchange="refreshCsvPreview()">
                        <option value="," {{ old('delimiter', ',') === ',' ? 'selected' : '' }}>Koma (,)</option>
                        <option value=";" {{ old('delimiter') === ';' ? 'selected' : '' }}>Semicolon (;)</option>
                    </select>
                    <p class="text-xs text-tertiary mt-1">Sesuaikan dengan pemisah kolom di file CSV Anda</p>
                </div>
                <div class="mb-4">
                     <label for="csv_file" class="block text-sm font-medium text-primary mb-2">
                         Pilih File CSV
                     </label>
                     <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                            class="admin-input focus:border-transparent"
                            onchange="validateFile(this)">
                     <p class="text-xs text-tertiary mt-1">Format: CSV, maksimal 2MB</p>
                     <div id="fileValidation" class="mt-2 text-sm"></div>
                     
                     <!-- CSV Preview -->
                     <div id="csvPreview" class="mt-3 hidden">
                         <h5 class="font-medium text-primary mb-2">Preview Data CSV:</h5>
                         <div id="previewContent" class="text-xs bg-gray-50 p-3 rounded border max-h-32 overflow-y-auto"></div>
                     </div>
                 </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="px-4 py-2 text-tertiary admin-input hover:bg-neutral">
                        Batal
                    </button>
                    <button type="submit" 
                            id="importButton"
                            class="admin-btn-primary">
                        Import
                    </button>
                </div>
            </form>
            
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h4 class="font-medium text-yellow-800 mb-2">Format CSV yang Diperlukan:</h4>
                <ul class="text-xs text-yellow-700 space-y-1">
                    <li>• Nama Lengkap (wajib)</li>
                    <li>• Tempat Lahir (opsional)</li>
                    <li>• Tanggal Lahir: YYYY-MM-DD (opsional)</li>
                    <li>• Jenis Kelamin: L/P (opsional)</li>
                    <li>• Alamat Rumah (opsional)</li>
                    <li>• Nomor Telepon (opsional)</li>
                    <li>• Email (wajib, unik)</li>
                    <li>• Instansi (opsional)</li>
                    <li>• Jabatan Saat Ini (opsional)</li>
                    <li>• Grade (opsional)</li>
                    <li>• PIN (wajib, unik, kombinasi huruf dan angka, 6-10 karakter)</li>
                </ul>
                <div class="mt-3 p-2 bg-orange-50 border border-orange-200 rounded">
                    <h5 class="font-medium text-orange-800 text-xs mb-1">Validasi Data:</h5>
                    <ul class="text-xs text-orange-700 space-y-1">
                        <li>• Email dan PIN harus unik (tidak boleh sama dengan peserta lain)</li>
                        <li>• Kombinasi Nama + Tanggal Lahir + Instansi + Grade tidak boleh sama</li>
                        <li>• Data yang duplikat akan dilewati dan ditampilkan di log</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Hapus peserta dengan SweetAlert
document.querySelectorAll('.btn-hapus-peserta').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const form = this.closest('.delete-peserta-form');
        const nama = form.getAttribute('data-nama');
        const terdaftar = form.getAttribute('data-terdaftar') === '1';
        const sesiNamesRaw = form.getAttribute('data-sesi-names') || '';

        if (terdaftar) {
            const sesiList = sesiNamesRaw
                ? sesiNamesRaw.split('|').map(s => `<li>${s}</li>`).join('')
                : '';

            Swal.fire({
                icon: 'error',
                title: 'Tidak Dapat Menghapus',
                html: `<p class="text-sm text-tertiary mb-2">Peserta <strong>${nama}</strong> terdaftar pada sesi assessment dan tidak dapat dihapus.</p>
                       ${sesiList ? `<ul class="text-sm text-left list-disc list-inside text-primary">${sesiList}</ul>` : ''}`,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc2626',
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Peserta?',
            html: `<p class="text-sm text-tertiary">Apakah Anda yakin ingin menghapus peserta <strong>${nama}</strong>?</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

// Flash: hapus diblokir dari server
@if(session('delete_blocked'))
    Swal.fire({
        icon: 'error',
        title: 'Tidak Dapat Menghapus',
        html: `<p class="text-sm text-tertiary mb-2">{{ session('error') }}</p>
               @if(session('blocked_sesi_names'))
               <ul class="text-sm text-left list-disc list-inside text-primary">
                   @foreach(session('blocked_sesi_names') as $sesiNama)
                   <li>{{ $sesiNama }}</li>
                   @endforeach
               </ul>
               @endif`,
        confirmButtonText: 'Mengerti',
        confirmButtonColor: '#dc2626',
    });
@endif

// Export CSV dengan filter aktif
document.getElementById('btnExportPeserta').addEventListener('click', function() {
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form));
    const delimiter = document.getElementById('export_delimiter').value;
    params.set('delimiter', delimiter);
    window.location.href = '{{ route('admin.peserta.export') }}?' + params.toString();
});

// Close modal when clicking outside
document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});

// File validation
function validateFile(input) {
    const file = input.files[0];
    const validationDiv = document.getElementById('fileValidation');
    const importButton = document.getElementById('importButton');
    const csvPreview = document.getElementById('csvPreview');
    const previewContent = document.getElementById('previewContent');
    
    if (file) {
        // Check file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            validationDiv.innerHTML = '<span class="text-red-600">❌ File terlalu besar (max 2MB)</span>';
            importButton.disabled = true;
            importButton.classList.add('opacity-50', 'cursor-not-allowed');
            csvPreview.classList.add('hidden');
            return;
        }
        
        // Check file type
        if (file.type !== 'text/csv' && !file.name.endsWith('.csv')) {
            validationDiv.innerHTML = '<span class="text-red-600">❌ Hanya file CSV yang diperbolehkan</span>';
            importButton.disabled = true;
            importButton.classList.add('opacity-50', 'cursor-not-allowed');
            csvPreview.classList.add('hidden');
            return;
        }
        
        validationDiv.innerHTML = '<span class="text-green-600">✅ File valid: ' + file.name + '</span>';
        importButton.disabled = false;
        importButton.classList.remove('opacity-50', 'cursor-not-allowed');
        
        // Show CSV preview
        showCsvPreview(file);
    } else {
        validationDiv.innerHTML = '';
        importButton.disabled = true;
        importButton.classList.add('opacity-50', 'cursor-not-allowed');
        csvPreview.classList.add('hidden');
    }
}

function getSelectedDelimiter() {
    return document.getElementById('delimiter').value;
}

function refreshCsvPreview() {
    const fileInput = document.getElementById('csv_file');
    if (fileInput.files && fileInput.files[0]) {
        showCsvPreview(fileInput.files[0]);
    }
}

// Show CSV preview
function showCsvPreview(file) {
    const csvPreview = document.getElementById('csvPreview');
    const previewContent = document.getElementById('previewContent');
    const delimiter = getSelectedDelimiter();
    const delimiterLabel = delimiter === ';' ? 'semicolon (;)' : 'koma (,)';
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const csv = e.target.result;
        const lines = csv.split('\n');
        const preview = lines.slice(0, 4).map((line, index) => {
            if (!line.trim()) {
                return '';
            }
            const cols = line.split(delimiter).map(c => c.trim().replace(/^"|"$/g, ''));
            const formatted = cols.join(' | ');
            if (index === 0) {
                return `<strong>Header (${delimiterLabel}):</strong> ${formatted}`;
            }
            return `<strong>Baris ${index}:</strong> ${formatted}`;
        }).filter(line => line).join('<br>');
        
        previewContent.innerHTML = preview || '<span class="text-tertiary">Tidak ada data untuk ditampilkan</span>';
        csvPreview.classList.remove('hidden');
    };
    reader.readAsText(file);
}

// Form submission
document.getElementById('importForm').addEventListener('submit', function(e) {
    const importButton = document.getElementById('importButton');
    const originalText = importButton.textContent;
    
    // Show loading state
    importButton.disabled = true;
    importButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Importing...';
    importButton.classList.add('opacity-75');
    
    // Form will submit normally
});
</script>
@endsection