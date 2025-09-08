@extends('admin.layouts.app')

@section('title', 'Manajemen Peserta')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Peserta</h1>
                <p class="text-sm text-gray-600">Kelola data peserta assessment</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" 
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Import CSV
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
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
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <h4 class="font-bold mb-2">Error saat import:</h4>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->get('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($errors->has('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ $errors->first('error') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
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
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Daftar Peserta</h3>
                <p class="text-sm text-gray-600">Total: {{ $pesertaList->count() }} peserta</p>
            </div>
            
            @if($pesertaList->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Instansi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jabatan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pesertaList as $peserta)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">
                                                {{ strtoupper(substr($peserta->nama_lengkap, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $peserta->nama_lengkap }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            PIN: {{ $peserta->pin }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $peserta->email }}</div>
                                <div class="text-sm text-gray-500">{{ $peserta->nomor_telepon ?: '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $peserta->instansi ?: '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $peserta->jabatan_saat_ini ?: '-' }}</div>
                                <div class="text-sm text-gray-500">{{ $peserta->grade ?: '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $totalAssessment = $peserta->kemajuanPenilaian->count();
                                    $completedAssessment = $peserta->kemajuanPenilaian->where('status', 'selesai')->count();
                                    $progressPercentage = $totalAssessment > 0 ? round(($completedAssessment / $totalAssessment) * 100) : 0;
                                @endphp
                                <div class="text-sm text-gray-900">
                                    {{ $completedAssessment }}/{{ $totalAssessment }} selesai
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $progressPercentage }}%</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.peserta.show', $peserta->id) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.peserta.edit', $peserta->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.peserta.destroy', $peserta->id) }}" 
                                          method="POST" class="inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-12 text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada peserta</h3>
                <p class="text-gray-500 mb-4">Mulai dengan mengimport peserta dari file CSV atau tambah peserta secara manual.</p>
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Import CSV Pertama
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Import Peserta dari CSV</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
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
                <p class="text-xs text-gray-600">Template ini sudah berisi contoh data yang bisa langsung digunakan</p>
            </div>
            
            <form action="{{ route('admin.peserta.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                                 <div class="mb-4">
                     <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-2">
                         Pilih File CSV
                     </label>
                     <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            onchange="validateFile(this)">
                     <p class="text-xs text-gray-500 mt-1">Format: CSV, maksimal 2MB</p>
                     <div id="fileValidation" class="mt-2 text-sm"></div>
                     
                     <!-- CSV Preview -->
                     <div id="csvPreview" class="mt-3 hidden">
                         <h5 class="font-medium text-gray-700 mb-2">Preview Data CSV:</h5>
                         <div id="previewContent" class="text-xs bg-gray-50 p-3 rounded border max-h-32 overflow-y-auto"></div>
                     </div>
                 </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            id="importButton"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
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

<script>
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

// Show CSV preview
function showCsvPreview(file) {
    const csvPreview = document.getElementById('csvPreview');
    const previewContent = document.getElementById('previewContent');
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const csv = e.target.result;
        const lines = csv.split('\n');
        const preview = lines.slice(0, 4).map((line, index) => {
            if (index === 0) {
                return `<strong>Header:</strong> ${line}`;
            } else if (line.trim()) {
                return `<strong>Baris ${index}:</strong> ${line}`;
            }
            return '';
        }).filter(line => line).join('<br>');
        
        previewContent.innerHTML = preview;
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