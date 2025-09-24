@extends('admin.layouts.app')

@section('title', 'Buat Sesi Baru')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.sesi.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Buat Sesi Baru</h1>
                <p class="text-gray-600 mt-2">Buat sesi penilaian assessment baru</p>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
        <div id="flashError" data-message="{{ session('error') }}" style="display:none"></div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.sesi.store') }}" method="POST" id="sessionForm" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-white shadow rounded-lg">
            <!-- Session Details -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Sesi</h3>
            </div>
            
            <div class="px-6 py-4 space-y-4">
                <!-- Session Name -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Sesi *</label>
                    <input type="text" 
                           name="nama" 
                           id="nama" 
                           value="{{ old('nama') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-300 @enderror"
                           placeholder="Masukkan nama sesi"
                           required>
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label for="durasi_menit" class="block text-sm font-medium text-gray-700">Durasi (opsional)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" 
                               name="durasi_menit" 
                               id="durasi_menit" 
                               value="{{ old('durasi_menit') }}"
                               min="1"
                               class="block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('durasi_menit') border-red-300 @enderror"
                               placeholder="0">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">menit</span>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin menetapkan durasi</p>
                    @error('durasi_menit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                    <textarea name="catatan" 
                              id="catatan" 
                              rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('catatan') border-red-300 @enderror"
                              placeholder="Tambahkan catatan atau instruksi khusus untuk sesi ini">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Assessment Selection -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Pemilihan Assessment</h3>
                <p class="text-sm text-gray-600 mt-1">Pilih jenis assessment yang akan dijalankan dan tentukan urutannya</p>
            </div>

            <div class="px-6 py-4">
                <div id="assessmentContainer">
                    <!-- Assessment items will be added here -->
                </div>

                <div class="mt-4">
                    <button type="button" 
                            onclick="addAssessment()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Assessment
                    </button>
                </div>

                @error('assessments')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 text-right rounded-b-lg">
                <a href="{{ route('admin.sesi.index') }}" 
                   class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Buat Sesi
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Assessment Template (hidden) -->
<template id="assessmentTemplate">
    <div class="assessment-item border border-gray-200 rounded-lg p-4 mb-4 bg-gray-50">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-medium text-gray-900">Assessment #<span class="assessment-number"></span></h4>
            <button type="button" 
                    onclick="removeAssessment(this)"
                    class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Assessment Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Assessment *</label>
                <select name="assessments[INDEX][penilaian_id]" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Pilih Assessment</option>
                                            @foreach($assessmentTypes as $assessment)
                            <option value="{{ $assessment->id }}" data-jenis="{{ $assessment->jenis }}" data-file="{{ $assessment->file_pdf ?? '' }}" data-url="{{ $assessment->file_pdf ? Storage::url($assessment->file_pdf) : '' }}">{{ $assessment->nama }}</option>
                        @endforeach
                </select>
            </div>

            <!-- Model In-Tray (hanya muncul jika jenis assessment adalah in_tray) -->
            <div class="intray-model-section" style="display: none;">
                <label class="block text-sm font-medium text-gray-700">Model In-Tray *</label>
                <select name="assessments[INDEX][model_in_tray]" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="urutan">Urutan (Drag & Drop)</option>
                    <option value="prioritas">Prioritas (4 Kategori)</option>
                </select>
            </div>

            <!-- Order -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Urutan *</label>
                <input type="number" 
                       name="assessments[INDEX][urutan]" 
                       min="1"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="1"
                       required>
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Durasi (opsional)</label>
                <div class="relative">
                    <input type="number" 
                           name="assessments[INDEX][durasi_default]" 
                           min="1"
                           class="mt-1 block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                           placeholder="0">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">menit</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Instructions -->
        <div class="mt-3">
            <label class="block text-sm font-medium text-gray-700">Instruksi Khusus (opsional)</label>
            <textarea name="assessments[INDEX][instruksi_khusus]" 
                      rows="8"
                      class="instruksi-editor mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Instruksi khusus untuk assessment ini"></textarea>
        </div>

        <!-- PDF Upload for Case Study -->
        <div class="mt-3 pdf-upload-section" style="display: none;">
            <label class="block text-sm font-medium text-gray-700">Upload PDF Studi Kasus</label>
            <div class="mt-1 flex items-center space-x-3">
                <input type="file" 
                       name="assessments[INDEX][file_pdf]" 
                       accept=".pdf"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                       onchange="handlePdfUpload(this, INDEX)">
                <div class="pdf-status text-sm text-gray-600"></div>
            </div>
            <p class="mt-1 text-xs text-gray-500">Upload file PDF untuk deskripsi soal studi kasus (max 10MB)</p>
            
            <!-- Current PDF Display -->
            <div class="mt-2 current-pdf-display" style="display: none;">
                <p class="text-sm text-gray-600">PDF saat ini: <span class="current-pdf-name font-medium"></span></p>
                <div class="flex gap-2 mt-2">
                    <button type="button" 
                            onclick="previewCurrentPdf(INDEX)"
                            class="inline-flex items-center px-2 py-1 border border-blue-300 shadow-sm text-xs font-medium rounded text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        👁️ Preview PDF
                    </button>
                    <button type="button" 
                            onclick="deleteCurrentPdf(INDEX)"
                            class="inline-flex items-center px-2 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        🗑️ Hapus PDF
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Model In-Tray Selection - HIDDEN FIELD -->
        <input type="hidden" name="assessments[INDEX][model_in_tray]" value="urutan" class="model-in-tray-input">

        <!-- Memos untuk In-Tray -->
        <div class="mt-4 memo-section" style="display:none;">
            <div class="flex items-center justify-between">
                <label class="block text-sm font-medium text-gray-700">Memo In-Tray (bisa lebih dari 1)</label>
                <button type="button" onclick="addMemo(this)" class="text-blue-600 hover:text-blue-800 text-sm">+ Tambah Memo</button>
            </div>
            <div class="space-y-3 memo-container">
                <!-- memo items here -->
            </div>
            <p class="mt-1 text-xs text-gray-500">Gunakan format text, bisa diberi styling via editor.</p>
        </div>
    </div>
</template>
@endsection

<!-- PDF Preview Modal -->
<div id="pdfPreviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Preview PDF</h3>
                <button onclick="closePdfPreview()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="pdfPreviewContent" class="w-full h-[80vh] border rounded-lg overflow-hidden">
                <div class="flex items-center justify-center h-full text-gray-500">
                    Memuat PDF...
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
let assessmentIndex = 0;

function addAssessment() {
    const container = document.getElementById('assessmentContainer');
    const template = document.getElementById('assessmentTemplate');
    const clone = template.content.cloneNode(true);
    
    // Update all INDEX placeholders
    const elements = clone.querySelectorAll('[name*="INDEX"]');
    elements.forEach(element => {
        element.name = element.name.replace('INDEX', assessmentIndex);
    });
    
    // Update assessment number
    const numberSpan = clone.querySelector('.assessment-number');
    if (numberSpan) {
        numberSpan.textContent = assessmentIndex + 1;
    }
    
    // Set default order equal to displayed number
    const orderInput = clone.querySelector('input[name*="[urutan]"]');
    if (orderInput) {
        orderInput.value = assessmentIndex + 1;
    }

    container.appendChild(clone);
    assessmentIndex++;
    
    // Add at least one assessment
    if (assessmentIndex === 1) {
        updateOrderNumbers();
    }
    
    // Update available options after adding new assessment
    updateAvailableOptions();
}

function removeAssessment(button) {
    const assessmentItem = button.closest('.assessment-item');
    assessmentItem.remove();
    updateOrderNumbers();
}

function updateOrderNumbers() {
    const items = document.querySelectorAll('.assessment-item');
    items.forEach((item, index) => {
        const numberSpan = item.querySelector('.assessment-number');
        if (numberSpan) {
            numberSpan.textContent = index + 1;
        }
        const orderInput = item.querySelector('input[name*="[urutan]"]');
        if (orderInput) {
            orderInput.value = index + 1;
        }
    });
}

// Add first assessment when page loads
document.addEventListener('DOMContentLoaded', function() {
    addAssessment();
    updateAvailableOptions();
    // Inisialisasi CKEditor untuk instruksi (bukan memo)
    if (window.ClassicEditor) {
        document.querySelectorAll('.instruksi-editor').forEach(function(el) {
            ClassicEditor.create(el, {
                toolbar: {
                    items: [
                        'bold', 'italic', 'underline', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'link', '|',
                        'undo', 'redo'
                    ]
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                }
            }).catch(err => console.error(err));
        });
    }

    // Tampilkan notifikasi error dari flash (jika ada)
    const flashErrorDiv = document.getElementById('flashError');
    if (flashErrorDiv && flashErrorDiv.dataset.message) {
        showNotification(flashErrorDiv.dataset.message, 'error');
    }

    // Trigger togglePdfUpload for existing assessments
    document.querySelectorAll('select[name*="[penilaian_id]"]').forEach(function(select) {
        if (select.value) {
            togglePdfUpload(select);
        }
    });
});

// Function to update available options
function updateAvailableOptions() {
    const selectedValues = [];
    const selects = document.querySelectorAll('select[name*="[penilaian_id]"]');
    
    // Collect all selected values
    selects.forEach(select => {
        if (select.value) {
            selectedValues.push(select.value);
        }
    });
    
    // Update each select to disable/enable options
    selects.forEach(select => {
        const options = select.querySelectorAll('option');
        options.forEach(option => {
            if (option.value === '') {
                // Keep placeholder option enabled
                option.disabled = false;
            } else if (selectedValues.includes(option.value) && select.value !== option.value) {
                // Disable if selected elsewhere
                option.disabled = true;
            } else {
                // Enable if not selected elsewhere
                option.disabled = false;
            }
        });
    });
}

// Form validation
document.getElementById('sessionForm').addEventListener('submit', function(e) {
    const assessments = document.querySelectorAll('.assessment-item');
    if (assessments.length === 0) {
        e.preventDefault();
        alert('Minimal harus ada satu assessment yang dipilih.');
        return false;
    }
    
    // Check if all required fields are filled
    let isValid = true;
    assessments.forEach((assessment, index) => {
        const penilaianId = assessment.querySelector('select[name*="[penilaian_id]"]').value;
        const urutan = assessment.querySelector('input[name*="[urutan]"]').value;
        
        if (!penilaianId || !urutan) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Semua field wajib diisi untuk setiap assessment.');
        return false;
    }
});

// Add event listeners for select changes
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.includes('[penilaian_id]')) {
        updateAvailableOptions();
        togglePdfUpload(e.target);
    }
});

// Function to toggle PDF upload section based on assessment type
function togglePdfUpload(selectElement) {
    const assessmentItem = selectElement.closest('.assessment-item');
    const pdfSection = assessmentItem.querySelector('.pdf-upload-section');
    const memoSection = assessmentItem.querySelector('.memo-section');
    const intrayModelSection = assessmentItem.querySelector('.intray-model-section');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    
    // PDF upload tersedia untuk studi_kasus, roleplay, dan fgd
    if (selectedOption && (selectedOption.dataset.jenis === 'studi_kasus' || 
                          selectedOption.dataset.jenis === 'roleplay' || 
                          selectedOption.dataset.jenis === 'fgd')) {
        pdfSection.style.display = 'block';
        // Check if there's existing PDF
        checkExistingPdf(assessmentItem, selectedOption.value, selectedOption.dataset.file);
        
        // Update label berdasarkan jenis assessment
        const pdfLabel = pdfSection.querySelector('label');
        if (pdfLabel) {
            if (selectedOption.dataset.jenis === 'studi_kasus') {
                pdfLabel.textContent = 'Upload PDF Studi Kasus';
            } else if (selectedOption.dataset.jenis === 'roleplay') {
                pdfLabel.textContent = 'Upload PDF Role-Play';
            } else if (selectedOption.dataset.jenis === 'fgd') {
                pdfLabel.textContent = 'Upload PDF LGD/FGD';
            }
        }
        
        // Update description
        const pdfDescription = pdfSection.querySelector('p.text-xs');
        if (pdfDescription) {
            if (selectedOption.dataset.jenis === 'studi_kasus') {
                pdfDescription.textContent = 'Upload file PDF untuk deskripsi soal studi kasus (max 10MB)';
            } else if (selectedOption.dataset.jenis === 'roleplay') {
                pdfDescription.textContent = 'Upload file PDF untuk skenario dan instruksi role-play (max 10MB)';
            } else if (selectedOption.dataset.jenis === 'fgd') {
                pdfDescription.textContent = 'Upload file PDF untuk topik dan panduan LGD/FGD (max 10MB)';
            }
        }
        
        if (memoSection) memoSection.style.display = 'none';
        if (intrayModelSection) intrayModelSection.style.display = 'none';
    } else {
        pdfSection.style.display = 'none';
        // Clear PDF input when hiding
        const pdfInput = pdfSection.querySelector('input[type="file"]');
        if (pdfInput) {
            pdfInput.value = '';
        }
    }

    if (selectedOption && selectedOption.dataset.jenis === 'in_tray') {
        if (memoSection) memoSection.style.display = 'block';
        if (intrayModelSection) {
            intrayModelSection.style.display = 'block';
            // Set default value untuk model_in_tray hanya jika belum ada nilai
            const modelSelect = intrayModelSection.querySelector('select[name*="[model_in_tray]"]');
            if (modelSelect && !modelSelect.value) {
                // Set default value ke 'urutan'
                modelSelect.value = 'urutan';
                console.log('Setting model_in_tray default to urutan');
            }
        }
    } else {
        if (memoSection) memoSection.style.display = 'none';
        if (memoSection) memoSection.querySelector('.memo-container').innerHTML = '';
        if (intrayModelSection) intrayModelSection.style.display = 'none';
    }
}

// Function to check existing PDF for assessment
function checkExistingPdf(assessmentItem, penilaianId, existingFile) {
    const currentPdfDisplay = assessmentItem.querySelector('.current-pdf-display');
    const currentPdfName = assessmentItem.querySelector('.current-pdf-name');
    if (existingFile && currentPdfDisplay && currentPdfName) {
        currentPdfDisplay.style.display = 'block';
        currentPdfName.textContent = existingFile.split('/').pop();
    } else if (currentPdfDisplay) {
        currentPdfDisplay.style.display = 'none';
    }
}

// Function to handle PDF upload
function handlePdfUpload(inputElement, index) {
    const file = inputElement.files[0];
    const statusDiv = inputElement.parentElement.querySelector('.pdf-status');
    
    if (file) {
        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            statusDiv.textContent = 'Error: File terlalu besar (max 10MB)';
            statusDiv.className = 'pdf-status text-sm text-red-600';
            inputElement.value = '';
            return;
        }
        
        // Validate file type
        if (file.type !== 'application/pdf') {
            statusDiv.textContent = 'Error: Hanya file PDF yang diperbolehkan';
            statusDiv.className = 'pdf-status text-sm text-red-600';
            inputElement.value = '';
            return;
        }
        
        statusDiv.textContent = `File dipilih: ${file.name}`;
        statusDiv.className = 'pdf-status text-sm text-green-600';
    } else {
        statusDiv.textContent = '';
    }
}

// PDF Preview functionality
function previewCurrentPdf(index) {
    const assessmentItem = document.querySelectorAll('.assessment-item')[index] || 
                          document.querySelector(`[data-assessment-index="${index}"]`);
    
    if (!assessmentItem) {
        console.error('Assessment item not found for index:', index);
        return;
    }
    
    const selectElement = assessmentItem.querySelector('select[name*="[penilaian_id]"]');
    if (!selectElement) {
        console.error('Select element not found');
        return;
    }
    
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    if (!selectedOption || !selectedOption.dataset.file) {
        alert('Tidak ada PDF yang tersedia untuk di-preview');
        return;
    }
    
    const pdfFile = selectedOption.dataset.file;
    const penilaianId = selectedOption.value;
    
    // Show modal
    const modal = document.getElementById('pdfPreviewModal');
    const content = document.getElementById('pdfPreviewContent');
    
    if (!modal || !content) {
        console.error('PDF preview modal elements not found');
        return;
    }
    
    modal.classList.remove('hidden');
    content.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Memuat PDF...</div>';
    
    // Build PDF URL
    const pdfUrl = `/admin/assessment/${penilaianId}/pdf/${pdfFile}`;
    
    // Disable right-click context menu
    content.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        return false;
    });
    
    // Disable keyboard shortcuts for save/print
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'p' || e.key === 'a')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Fetch PDF as blob to prevent direct access
    fetch(pdfUrl)
        .then(response => response.blob())
        .then(blob => {
            const blobUrl = URL.createObjectURL(blob);
            
            // Create PDF embed with blob URL and responsive controls
            const embed = document.createElement('embed');
            embed.type = 'application/pdf';
            embed.src = blobUrl + '#toolbar=0&navpanes=0&view=FitH&zoom=page-width';
            embed.className = 'w-full h-full';
            
            // Add responsive CSS
            const style = document.createElement('style');
            style.textContent = `
                #pdfPreviewContent embed {
                    max-width: 100%;
                    height: auto;
                    min-height: 500px;
                }
                @media (max-width: 768px) {
                    #pdfPreviewContent embed {
                        min-height: 400px;
                    }
                }
            `;
            document.head.appendChild(style);
            
            embed.onload = function() {
                content.innerHTML = '';
                content.appendChild(embed);
            };
            
            embed.onerror = function() {
                content.innerHTML = '<div class="flex items-center justify-center h-full text-red-500">Error: Gagal memuat PDF</div>';
            };
            
            // Cleanup blob URL when modal closes
            const modal = document.getElementById('pdfPreviewModal');
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        if (modal.classList.contains('hidden')) {
                            URL.revokeObjectURL(blobUrl);
                            observer.disconnect();
                        }
                    }
                });
            });
            observer.observe(modal, { attributes: true });
        })
        .catch(function(error) {
            console.error('Error fetching PDF:', error);
            content.innerHTML = '<div class="flex items-center justify-center h-full text-red-500">Error: Gagal mengambil PDF</div>';
        });
}

function closePdfPreview() {
    const modal = document.getElementById('pdfPreviewModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('pdfPreviewModal');
    if (modal && e.target === modal) {
        closePdfPreview();
    }
});

// Toast Notification util
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-xl transform transition-all duration-300 ease-in-out translate-x-full opacity-0 ${
        type === 'success'
            ? 'bg-green-500 text-white border-l-4 border-green-600'
            : 'bg-red-500 text-white border-l-4 border-red-600'
    }`;
    const icon = type === 'success'
        ? '<svg class="w-5 h-5 mr-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
        : '<svg class="w-5 h-5 mr-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
    notification.innerHTML = icon + message;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.classList.add('translate-x-0', 'opacity-100');
    }, 100);
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

// Function to delete current PDF
function deleteCurrentPdf(index) {
    if (confirm('Apakah Anda yakin ingin menghapus PDF ini?')) {
        const assessmentItem = document.querySelectorAll('.assessment-item')[index] || 
                              document.querySelector(`[data-assessment-index="${index}"]`);
        
        if (!assessmentItem) {
            console.error('Assessment item not found for index:', index);
            return;
        }
        
        const currentPdfDisplay = assessmentItem.querySelector('.current-pdf-display');
        const currentPdfName = assessmentItem.querySelector('.current-pdf-name');
        
        if (currentPdfDisplay) {
            currentPdfDisplay.style.display = 'none';
        }
        
        if (currentPdfName) {
            currentPdfName.textContent = '';
        }
        
        // Clear any file input
        const pdfInput = assessmentItem.querySelector('input[type="file"]');
        if (pdfInput) {
            pdfInput.value = '';
        }
        
        // Clear PDF status
        const statusDiv = assessmentItem.querySelector('.pdf-status');
        if (statusDiv) {
            statusDiv.textContent = '';
            statusDiv.className = 'pdf-status text-sm text-gray-600';
        }
        
        // Show success message
        showNotification('PDF berhasil dihapus', 'success');
    }
}

// Memo handling
function addMemo(button) {
    const section = button.closest('.memo-section');
    const container = section.querySelector('.memo-container');
    const memoIndex = container.children.length;
    const wrapper = document.createElement('div');
    wrapper.className = 'border border-gray-200 rounded p-3 bg-white';
    wrapper.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm text-gray-600">Memo ${memoIndex + 1}</span>
            <button type="button" class="text-red-600 hover:text-red-800 text-xs" onclick="this.closest('div.border').remove()">Hapus</button>
        </div>
        <textarea name="assessments[INDEX][memos][]" class="memo-editor w-full" rows="10" placeholder="Tulis isi memo di sini..."></textarea>
    `;
    // Sesuaikan INDEX pada name textarea
    wrapper.querySelectorAll('textarea[name*="assessments[INDEX]"]').forEach(el => {
        el.name = el.name.replace('INDEX', getAssessmentIndexFromElement(section.closest('.assessment-item')));
    });
    container.appendChild(wrapper);

    if (window.ClassicEditor) {
        ClassicEditor.create(wrapper.querySelector('textarea'), {
            toolbar: {
                items: [
                    'bold', 'italic', 'underline', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'link', '|',
                    'undo', 'redo'
                ]
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            }
        }).catch(err => console.error(err));
    }
}

function getAssessmentIndexFromElement(item) {
    const anyInput = item.querySelector('input[name*="assessments["]') || item.querySelector('select[name*="assessments["]') || item.querySelector('textarea[name*="assessments["]');
    if (!anyInput) return 0;
    const match = anyInput.name.match(/assessments\[(\d+)\]/);
    return match ? match[1] : 0;
}

// Toast Notification util (disamakan dengan index)
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-xl transform transition-all duration-300 ease-in-out translate-x-full opacity-0 ${
        type === 'success'
            ? 'bg-green-500 text-white border-l-4 border-green-600'
            : 'bg-red-500 text-white border-l-4 border-red-600'
    }`;
    const icon = type === 'success'
        ? '<svg class="w-5 h-5 mr-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
        : '<svg class="w-5 h-5 mr-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
    notification.innerHTML = icon + message;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.classList.add('translate-x-0', 'opacity-100');
    }, 100);
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

// Add form submission logging
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('=== FORM SUBMISSION DEBUG (CREATE) ===');
            
            // Log all form data
            const formData = new FormData(form);
            const formObject = {};
            for (let [key, value] of formData.entries()) {
                if (formObject[key]) {
                    if (Array.isArray(formObject[key])) {
                        formObject[key].push(value);
                    } else {
                        formObject[key] = [formObject[key], value];
                    }
                } else {
                    formObject[key] = value;
                }
            }
            
            console.log('Form Data:', formObject);
            
            // Log assessment data specifically
            const assessments = {};
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('assessments[')) {
                    const match = key.match(/assessments\[(\d+)\]\[([^\]]+)\]/);
                    if (match) {
                        const index = match[1];
                        const field = match[2];
                        if (!assessments[index]) {
                            assessments[index] = {};
                        }
                        assessments[index][field] = value;
                    }
                }
            }
            
            console.log('Assessment Data:', assessments);
            
            // Log model_in_tray specifically
            for (let index in assessments) {
                const assessment = assessments[index];
                console.log(`Assessment ${index}:`, {
                    penilaian_id: assessment.penilaian_id,
                    model_in_tray: assessment.model_in_tray || 'NOT SET',
                    urutan: assessment.urutan,
                    durasi_default: assessment.durasi_default,
                    instruksi_khusus: assessment.instruksi_khusus
                });
            }
            
            console.log('=== END FORM SUBMISSION DEBUG (CREATE) ===');
        });
    }
});
</script>
@endsection
