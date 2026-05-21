@extends('admin.layouts.app')

@section('title', 'Edit Sesi')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Edit Sesi',
    'subtitle' => 'Edit sesi: ' . $sesi->nama,
    'actions' => '<a href="' . route('admin.sesi.show', $sesi->id) . '" class="admin-btn-secondary">Kembali</a>',
])

@include('admin.partials.alerts')

    <!-- Success/Error Messages -->
    @if(session('error'))
        <div class="admin-alert admin-alert-error mb-4">
            <p class="font-semibold">{{ session('error') }}</p>
            @if(session('error_detail'))
                <p class="mt-2 text-sm"><strong>Detail error:</strong> {{ session('error_detail') }}</p>
            @endif
            @if(session('error_location'))
                <p class="mt-1 text-sm text-red-600"><strong>Lokasi:</strong> {{ session('error_location') }}</p>
            @endif
            @if(session('error_exception'))
                <p class="mt-1 text-xs text-red-500"><strong>Exception:</strong> {{ session('error_exception') }}</p>
            @endif
        </div>
        <div id="flashError" data-message="{{ session('error') }}" style="display:none"></div>
    @endif

    @if ($errors->any())
        <div class="admin-alert admin-alert-error mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.sesi.update', $sesi->id) }}" method="POST" id="sessionForm" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')
        
        <div class="admin-card">
            <!-- Session Details -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-primary">Informasi Sesi</h3>
            </div>
            
            <div class="px-6 py-4 space-y-4">
                <!-- Session Name -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-primary">Nama Sesi *</label>
                    <input type="text" 
                           name="nama" 
                           id="nama" 
                           value="{{ old('nama', $sesi->nama) }}"
                           class="mt-1 block w-full admin-input @error('nama') border-red-300 @enderror"
                           placeholder="Masukkan nama sesi"
                           required>
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label for="durasi_menit" class="block text-sm font-medium text-primary">Durasi (opsional)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" 
                               name="durasi_menit" 
                               id="durasi_menit" 
                               value="{{ old('durasi_menit', $sesi->durasi_menit) }}"
                               min="1"
                               class="block w-full pr-12 admin-input @error('durasi_menit') border-red-300 @enderror"
                               placeholder="0">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-tertiary sm:text-sm">menit</span>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-tertiary">Kosongkan jika tidak ingin menetapkan durasi</p>
                    @error('durasi_menit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="catatan" class="block text-sm font-medium text-primary">Catatan (opsional)</label>
                    <textarea name="catatan" 
                              id="catatan" 
                              rows="3"
                              class="catatan-editor mt-1 block w-full admin-input @error('catatan') border-red-300 @enderror"
                              placeholder="Tambahkan catatan atau instruksi khusus untuk sesi ini">{!! old('catatan', $sesi->catatan ?? '') !!}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Assessment Selection -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-primary">Pemilihan Assessment</h3>
                <p class="text-sm text-tertiary mt-1">Pilih jenis assessment yang akan dijalankan dan tentukan urutannya</p>
            </div>

            <div class="px-6 py-4">
                <div id="assessmentContainer">
                    <!-- Assessment items will be added here -->
                </div>

                <div class="mt-4">
                    <button type="button" 
                            id="addAssessmentBtn"
                            onclick="addAssessment()"
                            class="admin-btn-secondary disabled:opacity-50 disabled:cursor-not-allowed">
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
                <a href="{{ route('admin.sesi.show', $sesi->id) }}" 
                   class="admin-btn-secondary mr-3">
                    Batal
                </a>
                <button type="submit" 
                        class="admin-btn-primary">
                    Update Sesi
                </button>
            </div>
        </div>
    </form>

<!-- Existing assessments data for JS (serialized as JSON) -->
<script type="application/json" id="existingAssessmentsData">@json($existingAssessments ?? $sesi->assessments->sortBy('urutan')->values())</script>

<!-- Assessment Template (hidden) -->
<template id="assessmentTemplate">
    <div class="assessment-item border border-gray-200 rounded-lg p-4 mb-4 bg-gray-50">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-medium text-primary">Assessment #<span class="assessment-number"></span></h4>
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
                <label class="block text-sm font-medium text-primary">Jenis Assessment *</label>
                <select name="assessments[INDEX][penilaian_id]" 
                        class="mt-1 block w-full admin-input"
                        required>
                    <option value="">Pilih Assessment</option>
                    @php
                        // Pastikan hanya jenis yang diizinkan yang ditampilkan
                        $allowedTypes = ($sesi->id > 12) ? ['studi_kasus', 'fgd', 'in_tray', 'roleplay'] : ['studi_kasus', 'in_tray', 'roleplay', 'fgd'];
                        $filteredAssessments = $assessmentTypes->filter(function($assessment) use ($allowedTypes) {
                            return in_array($assessment->jenis, $allowedTypes);
                        });
                    @endphp
                    @foreach($filteredAssessments as $assessment)
                        <option value="{{ $assessment->id }}" data-jenis="{{ $assessment->jenis }}" data-file="{{ $assessment->file_pdf ?? '' }}" data-url="{{ $assessment->file_pdf ? route('assessment.pdf.view.id', $assessment->id) : '' }}">{{ $assessment->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Model In-Tray (hanya muncul jika jenis assessment adalah in_tray) -->
            <div class="intray-model-section" style="display: none;">
                <label class="block text-sm font-medium text-primary">Model In-Tray *</label>
                <select name="assessments[INDEX][model_in_tray]" 
                        class="mt-1 block w-full admin-input">
                    <option value="urutan">Urutan (Drag & Drop)</option>
                    <option value="prioritas">Prioritas (4 Kategori)</option>
                </select>
            </div>


            <!-- Order -->
            <div>
                <label class="block text-sm font-medium text-primary">Urutan *</label>
                <input type="number" 
                       name="assessments[INDEX][urutan]" 
                       min="1"
                       class="mt-1 block w-full admin-input"
                       placeholder="1"
                       required>
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-sm font-medium text-primary">Durasi (opsional)</label>
                <div class="relative">
                    <input type="number" 
                           name="assessments[INDEX][durasi_default]" 
                           min="1"
                           class="mt-1 block w-full pr-12 admin-input"
                           placeholder="0">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-tertiary sm:text-sm">menit</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Instructions -->
        <div class="mt-3">
            <label class="block text-sm font-medium text-primary">Instruksi Khusus (opsional)</label>
            <textarea name="assessments[INDEX][instruksi_khusus]" 
                      id="instruksi-editor-INDEX"
                      rows="8"
                      class="instruksi-editor mt-1 block w-full admin-input"
                      placeholder="Instruksi khusus untuk assessment ini"></textarea>
        </div>

        <!-- PDF Upload for Case Study -->
        <div class="mt-4 pdf-upload-section" style="display: none;">
            <div class="border-t border-b border-gray-300 bg-gray-50 py-4 px-4 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <label class="ml-2 block text-sm font-semibold text-primary">Upload PDF Studi Kasus</label>
                </div>
                <div class="mt-1">
                    <input type="file" 
                           name="assessments[INDEX][file_pdf]" 
                           accept=".pdf"
                           class="block w-full text-sm text-tertiary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           onchange="handlePdfUpload(this, INDEX)">
                    <div class="pdf-status text-sm text-tertiary mt-1"></div>
                </div>
                <p class="mt-2 text-xs text-tertiary">Upload file PDF untuk deskripsi soal studi kasus (max 10MB)</p>
                
                <!-- Current PDF Display -->
                <div class="mt-3 current-pdf-display" style="display: none;">
                    <div class="bg-white border border-gray-200 rounded-md p-3">
                        <p class="text-sm text-primary mb-2">
                            <span class="font-medium">PDF saat ini:</span> 
                            <span class="current-pdf-name font-semibold text-blue-600"></span>
                        </p>
                        <div class="flex gap-2">
                            <button type="button" 
                                    onclick="previewCurrentPdf(INDEX)"
                                    class="inline-flex items-center px-3 py-1.5 border border-blue-300 shadow-sm text-xs font-medium rounded text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                👁️ Preview PDF
                            </button>
                            <button type="button" 
                                    onclick="deleteCurrentPdf(INDEX)"
                                    class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                🗑️ Hapus PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Model In-Tray Selection - HIDDEN FIELD REMOVED (duplikat dengan select element) -->

        <!-- Memos untuk In-Tray -->
        <div class="mt-4 memo-section" style="display:none;">
            <div class="flex items-center justify-between">
                <label class="block text-sm font-medium text-primary">Memo In-Tray (bisa lebih dari 1)</label>
                <button type="button" onclick="addMemo(this)" class="text-blue-600 hover:text-blue-800 text-sm">+ Tambah Memo</button>
            </div>
            <div class="space-y-3 memo-container">
                <!-- memo items here -->
            </div>
            <p class="mt-1 text-xs text-tertiary">Gunakan format text, bisa diberi styling via editor.</p>
        </div>
    </div>
</template>
@endsection

<!-- PDF Preview Modal -->
<div id="pdfPreviewModal" class="admin-modal hidden" role="dialog" aria-modal="true">
    <div class="admin-modal-panel admin-modal-panel-pdf">
        <div class="flex justify-between items-center gap-3 mb-3 flex-shrink-0">
            <h3 class="text-lg font-medium text-primary">Preview PDF</h3>
            <div class="flex items-center gap-2">
                <a id="pdfPreviewOpenTab" href="#" target="_blank" rel="noopener noreferrer" class="admin-btn-secondary text-sm whitespace-nowrap">
                    Buka di Tab Baru
                </a>
                <button type="button" onclick="closePdfPreview()" class="p-2 text-tertiary hover:text-primary rounded-lg hover:bg-neutral" aria-label="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="pdfPreviewContent" class="admin-pdf-preview-body">
            <div class="admin-pdf-preview-loading">Memuat PDF...</div>
        </div>
    </div>
</div>

@section('scripts')
<script>
/* eslint-disable */
let assessmentIndex = 0;
const sesiIdValue = "{{ $sesi->id }}";
const useNewSystemGlobal = Number(sesiIdValue) >= 13;

function addAssessment(data = null) {
    const container = document.getElementById('assessmentContainer');
    const template = document.getElementById('assessmentTemplate');
    const clone = template.content.cloneNode(true);
    
    const assessmentItem = clone.querySelector('.assessment-item');
    if (assessmentItem) {
        // Tandai apakah assessment berasal dari data existing
        assessmentItem.dataset.existing = data ? 'true' : 'false';
    }
    
    // Update all INDEX placeholders
    const elements = clone.querySelectorAll('[name*="INDEX"]');
    elements.forEach(element => {
        element.name = element.name.replace('INDEX', assessmentIndex);
    });
    
    // Update ID placeholders
    const idElements = clone.querySelectorAll('[id*="INDEX"]');
    idElements.forEach(element => {
        element.id = element.id.replace('INDEX', assessmentIndex);
    });
    
    // Update onclick handlers with INDEX placeholders
    const onclickElements = clone.querySelectorAll('[onclick*="INDEX"]');
    onclickElements.forEach(element => {
        const originalOnclick = element.getAttribute('onclick');
        if (originalOnclick) {
            element.setAttribute('onclick', originalOnclick.replace(/INDEX/g, assessmentIndex));
        }
    });
    
    // Update onchange handlers with INDEX placeholders
    const onchangeElements = clone.querySelectorAll('[onchange*="INDEX"]');
    onchangeElements.forEach(element => {
        const originalOnchange = element.getAttribute('onchange');
        if (originalOnchange) {
            element.setAttribute('onchange', originalOnchange.replace(/INDEX/g, assessmentIndex));
        }
    });
    
    // Update assessment number
    const numberSpan = clone.querySelector('.assessment-number');
    if (numberSpan) {
        numberSpan.textContent = assessmentIndex + 1;
    }

    // Set default order equal to displayed number if no data
    const orderInput = clone.querySelector('input[name*="[urutan]"]');
    if (orderInput && !data) {
        orderInput.value = assessmentIndex + 1;
    }
    
        // Pre-fill data if editing
        if (data) {
            const penilaianSelect = clone.querySelector('select[name*="[penilaian_id]"]');
            const urutanInput = clone.querySelector('input[name*="[urutan]"]');
            const durasiInput = clone.querySelector('input[name*="[durasi_default]"]');
            const instruksiTextarea = clone.querySelector('textarea[name*="[instruksi_khusus]"]');
            const kategoriSelect = clone.querySelector('select[name*="[kategori_studi_kasus_id]"]');
            
            if (penilaianSelect) penilaianSelect.value = data.penilaian_id;
            if (urutanInput) urutanInput.value = data.urutan;
            if (durasiInput) durasiInput.value = data.durasi_default || '';
            if (instruksiTextarea) instruksiTextarea.value = data.instruksi_khusus || '';

            // Tampilkan section upload PDF jika jenis adalah studi_kasus, roleplay, fgd, atau in_tray
            const selectedOption = penilaianSelect ? penilaianSelect.options[penilaianSelect.selectedIndex] : null;
            if (selectedOption && (selectedOption.dataset.jenis === 'studi_kasus' || 
                                  selectedOption.dataset.jenis === 'roleplay' || 
                                  selectedOption.dataset.jenis === 'fgd' ||
                                  selectedOption.dataset.jenis === 'in_tray')) {
                const pdfSection = clone.querySelector('.pdf-upload-section');
                if (pdfSection) {
                    pdfSection.style.display = 'block';
                    const currentPdfDisplay = pdfSection.querySelector('.current-pdf-display');
                    const currentPdfName = pdfSection.querySelector('.current-pdf-name');
                    const existingFile = selectedOption ? (selectedOption.dataset.file || '') : '';
                    if (existingFile && currentPdfDisplay && currentPdfName) {
                        currentPdfDisplay.style.display = 'block';
                        currentPdfName.textContent = existingFile.split('/').pop();
                    }
                }
                
                // Tampilkan section kategori studi kasus jika jenis adalah studi_kasus DAN sesi_id >= 13
                const useNewSystem = useNewSystemGlobal;
                // Note: kategori_studi_kasus_id sudah dihapus, tidak perlu set kategori section lagi
            }

        // Tampilkan dan prefille memo jika jenis adalah in_tray
        if (selectedOption && selectedOption.dataset.jenis === 'in_tray') {
            const memoSection = clone.querySelector('.memo-section');
            const intrayModelSection = clone.querySelector('.intray-model-section');
            
            if (memoSection) {
                memoSection.style.display = 'block';
                const memoContainer = memoSection.querySelector('.memo-container');
                // Dedup konten memo berdasarkan index agar tidak dobel
                const memos = Array.isArray(data.memos) ? Array.from(new Set(data.memos)) : [];
                memos.forEach((content, idx) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'border border-gray-200 rounded p-3 bg-white';
                    wrapper.innerHTML = `
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-tertiary">Memo ${idx + 1}</span>
                            <button type="button" class="text-red-600 hover:text-red-800 text-xs" onclick="this.closest('div.border').remove()">Hapus</button>
                        </div>
                        <textarea name="assessments[INDEX][memos][]" id="memo-editor-INDEX-${idx}" class="memo-editor w-full" rows="10"></textarea>
                    `;
                    wrapper.querySelectorAll('textarea[name*="assessments[INDEX]"]').forEach(el => {
                        el.name = el.name.replace('INDEX', assessmentIndex); // assessmentIndex saat ini mengacu pada item yang ditambahkan terakhir
                        el.value = content || '';
                    });
                    wrapper.querySelectorAll('textarea[id*="INDEX"]').forEach(el => {
                        el.id = el.id.replace('INDEX', assessmentIndex);
                    });
                    memoContainer.appendChild(wrapper);
                    setTimeout(function() {
                        if (window.initCKEditor && window.$) {
                            const ta = wrapper.querySelector('textarea');
                            // Force destroy any existing instance
                            if (window.ckeditorInstances && window.ckeditorInstances[ta.id]) {
                                try {
                                    $('#' + ta.id).summernote('destroy');
                                    delete window.ckeditorInstances[ta.id];
                                } catch (e) {
                                    // Silent fail
                                }
                            }
                            // Initialize new instance
                            try {
                                window.initCKEditor(ta.id);
                                
                                // Add event listener untuk memastikan content ter-sync saat user mengetik
                                ta.addEventListener('blur', function() {
                                    if (window.ckeditorInstances && window.ckeditorInstances[ta.id]) {
                                        try {
                                            const content = window.ckeditorInstances[ta.id].getData();
                                            ta.value = content;
                                        } catch (e) {
                                            // Silent fail
                                        }
                                    }
                                });
                                
                                // Juga sync saat form submit
                                ta.addEventListener('change', function() {
                                    if (window.ckeditorInstances && window.ckeditorInstances[ta.id]) {
                                        try {
                                            const content = window.ckeditorInstances[ta.id].getData();
                                            ta.value = content;
                                        } catch (e) {
                                            // Silent fail
                                        }
                                    }
                                });
                            } catch (e) {
                                // Silent fail
                            }
                        } else {
                            setTimeout(arguments.callee, 200);
                        }
                    }, 500);
                });
            }
            
            // Tampilkan dan isi section model in-tray
            if (intrayModelSection) {
                intrayModelSection.style.display = 'block';
                const modelSelect = intrayModelSection.querySelector('select[name*="[model_in_tray]"]');
                if (modelSelect) {
                    // Set berdasarkan data yang ada dari database, tidak ada default fallback
                    if (data.model_in_tray) {
                        modelSelect.value = data.model_in_tray;
                    }
                    // Jika tidak ada data.model_in_tray, biarkan select tidak terpilih (user harus memilih)
                    
                }
            }
        }
    }
    
    container.appendChild(clone);
    assessmentIndex++;
    
    // Update available options after adding new assessment
    updateAvailableOptions();
    checkAndUpdateAddButton();
    
    // Trigger togglePdfUpload untuk menampilkan section yang sesuai (termasuk kategori studi kasus)
    // Lakukan setelah clone ditambahkan ke DOM
    if (data && data.penilaian_id) {
        setTimeout(function() {
            const assessmentItem = container.querySelector('.assessment-item:last-child');
            if (!assessmentItem) return;
            
            const penilaianSelect = assessmentItem.querySelector('select[name*="[penilaian_id]"]');
            if (penilaianSelect && penilaianSelect.value) {
                // Trigger togglePdfUpload untuk menampilkan section yang sesuai
                togglePdfUpload(penilaianSelect);
                
                // Note: kategori_studi_kasus_id sudah dihapus, tidak perlu set kategori lagi
            }
        }, 300);
    }
    
    // Initialize Summernote for new assessment's instruction editor
    function initNewAssessmentSummernote() {
        const newInstructionEditor = clone.querySelector('.instruksi-editor');
        if (newInstructionEditor) {
            // Force destroy any existing instance
            if (window.ckeditorInstances && window.ckeditorInstances[newInstructionEditor.id]) {
                try {
                    $('#' + newInstructionEditor.id).summernote('destroy');
                    delete window.ckeditorInstances[newInstructionEditor.id];
                } catch (e) {
                    // Silent fail
                }
            }
            // Initialize new instance
            try {
                window.initCKEditor(newInstructionEditor.id);
            } catch (e) {
                // Silent fail
            }
        }
    }
    
    // Wait for dependencies and initialize
    function waitForDependenciesAndInit(callback) {
        if (window.$ && window.$.fn.summernote && window.initCKEditor) {
            callback();
        } else {
            setTimeout(() => waitForDependenciesAndInit(callback), 100);
        }
    }
    
    // Try multiple times to ensure initialization
    waitForDependenciesAndInit(initNewAssessmentSummernote);
    setTimeout(() => waitForDependenciesAndInit(initNewAssessmentSummernote), 500);
    setTimeout(() => waitForDependenciesAndInit(initNewAssessmentSummernote), 1000);
}

function removeAssessment(button) {
    const assessmentItem = button.closest('.assessment-item');
    
    // Clean up CKEditor instances before removing
    if (window.destroyCKEditor) {
        const editors = assessmentItem.querySelectorAll('.instruksi-editor, .memo-editor');
        editors.forEach(function(editor) {
            if (window.ckeditorInstances && window.ckeditorInstances[editor.id]) {
                window.destroyCKEditor(editor.id);
            }
        });
    }
    
    assessmentItem.remove();
    updateOrderNumbers();
    updateAvailableOptions();
    checkAndUpdateAddButton();
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

// Load existing assessments when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah sesi_id >= 13 untuk menggunakan sistem baru (kategori dari sesi)
    const useNewSystem = useNewSystemGlobal;
    
    // Ambil existingAssessments dari tag script JSON agar linter tidak error
    let existingAssessments = [];
    try {
        const jsonEl = document.getElementById('existingAssessmentsData');
        const jsonData = jsonEl ? jsonEl.textContent : '[]';
        existingAssessments = JSON.parse(jsonData);
    } catch (e) {
        existingAssessments = [];
    }
    
    if (existingAssessments.length > 0) {
        existingAssessments.forEach(function(assessment, index) {
            try {
                addAssessment({
                    penilaian_id: assessment.penilaian_id,
                    urutan: assessment.urutan,
                    durasi_default: assessment.durasi_default || '',
                    instruksi_khusus: assessment.instruksi_khusus || '',
                    model_in_tray: assessment.model_in_tray, // Gunakan nilai dari database tanpa default fallback
                    memos: assessment.memos || [],
                    kategori_studi_kasus_id: assessment.kategori_studi_kasus_id || null
                });
                
                // Setelah assessment ditambahkan, pastikan PDF yang sudah ada ditampilkan
                setTimeout(function() {
                    try {
                        const assessmentItems = document.querySelectorAll('.assessment-item');
                        const currentItem = assessmentItems[index];
                        if (currentItem) {
                            const selectElement = currentItem.querySelector('select[name*="[penilaian_id]"]');
                            if (selectElement) {
                                // Trigger togglePdfUpload untuk menampilkan PDF yang sudah ada
                                togglePdfUpload(selectElement);
                            }
                        }
                    } catch (e) {
                        // Silent fail
                    }
                }, 100 * (index + 1));
            } catch (error) {
                // Continue to next assessment even if one fails
            }
        });
        
        // Initialize Summernote for existing assessments after they are loaded
        setTimeout(function() {
            waitForDependencies(function() {
                document.querySelectorAll('.instruksi-editor').forEach(function(el) {
                    if (el.id && (!window.ckeditorInstances || !window.ckeditorInstances[el.id])) {
                        window.initCKEditor(el.id);
                    }
                });
            });
        }, 3000);
    } else {
        addAssessment();
    }
    
    // Update available options after loading existing assessments
    updateAvailableOptions();
    checkAndUpdateAddButton();
    
    // Note: kategori_studi_kasus_id sudah dihapus, tidak perlu set required lagi

    // Wait for jQuery and Summernote to be fully loaded
    function waitForDependencies(callback) {
        if (window.$ && window.$.fn.summernote && window.initCKEditor) {
            callback();
        } else {
            setTimeout(() => waitForDependencies(callback), 100);
        }
    }
    
    // Inisialisasi Summernote untuk instruksi dan catatan
    function initSummernote() {
        // Initialize instruction editors
        document.querySelectorAll('.instruksi-editor').forEach(function(el) {
            // Skip jika sudah diinisialisasi
            if (window.ckeditorInstances && window.ckeditorInstances[el.id]) {
                return;
            }
            
            window.initCKEditor(el.id);
        });
        // Initialize catatan editor
        document.querySelectorAll('.catatan-editor').forEach(function(el) {
            // Skip jika sudah diinisialisasi
            if (window.ckeditorInstances && window.ckeditorInstances[el.id]) {
                return;
            }
            
            window.initCKEditor(el.id);
        });
    }
    
    // Start initialization after dependencies are ready
    waitForDependencies(initSummernote);
    
    // Add event listener for DOM changes to reinitialize Summernote
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        const instructionEditors = node.querySelectorAll ? node.querySelectorAll('.instruksi-editor') : [];
                        const catatanEditors = node.querySelectorAll ? node.querySelectorAll('.catatan-editor') : [];
                        
                        instructionEditors.forEach(function(el) {
                            if (el.id && (!window.ckeditorInstances || !window.ckeditorInstances[el.id])) {
                                setTimeout(function() {
                                    window.initCKEditor(el.id);
                                }, 100);
                            }
                        });
                        
                        catatanEditors.forEach(function(el) {
                            if (el.id && (!window.ckeditorInstances || !window.ckeditorInstances[el.id])) {
                                setTimeout(function() {
                                    window.initCKEditor(el.id);
                                }, 100);
                            }
                        });
                    }
                });
            }
        });
    });
    
    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Force initialize Summernote for all existing assessments and catatan
    function forceInitSummernote() {
        // Initialize instruction editors
        document.querySelectorAll('.instruksi-editor').forEach(function(el) {
            if (el.id) {
                // Destroy existing instance if any
                if (window.ckeditorInstances && window.ckeditorInstances[el.id]) {
                    try {
                        $('#' + el.id).summernote('destroy');
                        delete window.ckeditorInstances[el.id];
                    } catch (e) {
                        // Silent fail
                    }
                }
                // Initialize new instance
                try {
                    window.initCKEditor(el.id);
                } catch (e) {
                    // Silent fail
                }
            }
        });
        // Initialize catatan editor
        document.querySelectorAll('.catatan-editor').forEach(function(el) {
            if (el.id) {
                // Destroy existing instance if any
                if (window.ckeditorInstances && window.ckeditorInstances[el.id]) {
                    try {
                        $('#' + el.id).summernote('destroy');
                        delete window.ckeditorInstances[el.id];
                    } catch (e) {
                        // Silent fail
                    }
                }
                // Initialize new instance
                try {
                    window.initCKEditor(el.id);
                } catch (e) {
                    // Silent fail
                }
            }
        });
    }
    
    // Try multiple times to ensure initialization
    setTimeout(forceInitSummernote, 1000);
    setTimeout(forceInitSummernote, 3000);
    setTimeout(forceInitSummernote, 5000);
    
    // Also add a global function to reinitialize all Summernote editors
    window.reinitializeAllSummernote = function() {
        // Reinitialize instruction editors
        document.querySelectorAll('.instruksi-editor').forEach(function(el) {
            if (el.id && (!window.ckeditorInstances || !window.ckeditorInstances[el.id])) {
                window.initCKEditor(el.id);
            }
        });
        // Reinitialize catatan editor
        document.querySelectorAll('.catatan-editor').forEach(function(el) {
            if (el.id && (!window.ckeditorInstances || !window.ckeditorInstances[el.id])) {
                window.initCKEditor(el.id);
            }
        });
    };

    // Trigger togglePdfUpload for existing assessments
    document.querySelectorAll('select[name*="[penilaian_id]"]').forEach(function(select) {
        if (select.value) {
            togglePdfUpload(select);
        }
    });

    // Tampilkan notifikasi error dari flash (jika ada)
    const flashErrorDiv = document.getElementById('flashError');
    if (flashErrorDiv && flashErrorDiv.dataset.message) {
        showNotification(flashErrorDiv.dataset.message, 'error');
    }
});

// Function to update available options
function updateAvailableOptions() {
    const selectedValues = [];
    const selects = document.querySelectorAll('select[name*="[penilaian_id]"]');
    
    // Collect all selected penilaian_id values
    selects.forEach(select => {
        if (select.value) {
            selectedValues.push(select.value);
        }
    });
    
    // Update each select to disable/enable options
    selects.forEach(select => {
        const currentValue = select.value;
        const options = select.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') {
                // Keep placeholder option enabled
                option.disabled = false;
            } else {
                // Cek apakah option ini sudah dipilih di select lain
                const isSelectedElsewhere = selectedValues.includes(option.value) && currentValue !== option.value;
                
                if (isSelectedElsewhere) {
                    // Sudah dipilih di tempat lain, disable
                    option.disabled = true;
                } else {
                    // Belum dipilih di tempat lain, enable
                    option.disabled = false;
                }
            }
        });
    });
}

// Function to check if all assessments are selected and disable add button
function checkAndUpdateAddButton() {
    const useNewSystem = useNewSystemGlobal;
    const addButton = document.getElementById('addAssessmentBtn');
    
    if (!addButton) return;
    
    const assessments = document.querySelectorAll('.assessment-item');
    const assessmentCount = assessments.length;
    
    if (useNewSystem) {
        // Untuk sesi_id > 12: maksimum 5 assessment
        // 2 studi kasus (BQ dan PQ) + 1 fgd + 1 in_tray + 1 roleplay
        const maxAssessments = 5;
        
        // Hitung jenis assessment yang sudah dipilih berdasarkan penilaian_id
        const selectedCounts = {
            studi_kasus_bq: 0,
            studi_kasus_pq: 0,
            fgd: 0,
            in_tray: 0,
            roleplay: 0
        };
        
        assessments.forEach(assessment => {
            const select = assessment.querySelector('select[name*="[penilaian_id]"]');
            if (select && select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const jenis = selectedOption ? selectedOption.dataset.jenis : '';
                const nama = selectedOption ? selectedOption.textContent.toUpperCase() : '';
                
                // Deteksi BQ atau PQ dari nama penilaian
                if (jenis === 'studi_kasus') {
                    if (nama.includes('BQ')) {
                        selectedCounts.studi_kasus_bq++;
                    } else if (nama.includes('PQ')) {
                        selectedCounts.studi_kasus_pq++;
                    }
                } else if (jenis === 'fgd') {
                    selectedCounts.fgd++;
                } else if (jenis === 'in_tray') {
                    selectedCounts.in_tray++;
                } else if (jenis === 'roleplay') {
                    selectedCounts.roleplay++;
                }
            }
        });
        
        // Cek apakah sudah mencapai maksimum
        // Untuk new system: max 1 BQ, 1 PQ, 1 FGD, 1 In-Tray, 1 Role-Play (total 5)
        const isMaxReached = 
            selectedCounts.studi_kasus_bq >= 1 &&
            selectedCounts.studi_kasus_pq >= 1 &&
            selectedCounts.fgd >= 1 &&
            selectedCounts.in_tray >= 1 &&
            selectedCounts.roleplay >= 1;
        
        if (isMaxReached) {
            addButton.disabled = true;
            addButton.title = 'Semua assessment sudah dipilih (maksimum: 1 Studi Kasus BQ, 1 Studi Kasus PQ, 1 FGD, 1 In-Tray, 1 Role-Play)';
        } else {
            addButton.disabled = false;
            addButton.title = '';
        }
    } else {
        // Untuk sesi_id <= 12, tidak ada batasan khusus
        // Tapi tetap perlu minimal 1 assessment
        if (assessmentCount >= 10) { // Batasan umum untuk mencegah terlalu banyak
            addButton.disabled = true;
            addButton.title = 'Maksimum assessment telah tercapai';
        } else {
            addButton.disabled = false;
            addButton.title = '';
        }
    }
}

// Form validation
document.getElementById('sessionForm').addEventListener('submit', function(e) {
    // PENTING: Prevent default dulu untuk mencegah browser validasi sebelum kita hapus required
    e.preventDefault();
    
    // Note: kategori_studi_kasus_id sudah dihapus, tidak perlu validasi lagi
    
    const assessments = document.querySelectorAll('.assessment-item');
    if (assessments.length === 0) {
        alert('Minimal harus ada satu assessment yang dipilih.');
        return false;
    }
    
    // Check if all required fields are filled
    let isValid = true;
    let errorMessage = '';
    const studiKasusData = []; // Untuk tracking studi kasus dengan kategori
    
    assessments.forEach((assessment, index) => {
        const penilaianSelect = assessment.querySelector('select[name*="[penilaian_id]"]');
        const urutan = assessment.querySelector('input[name*="[urutan]"]').value;
        
        const penilaianId = penilaianSelect ? penilaianSelect.value : '';
        if (!penilaianId || !urutan) {
            isValid = false;
            errorMessage = 'Semua field wajib diisi untuk setiap assessment.';
        }
    });
    
    // Sync CKEditor content to textareas before submission
    document.querySelectorAll('.memo-editor').forEach(function(textarea) {
        if (window.ckeditorInstances && window.ckeditorInstances[textarea.id]) {
            try {
                const content = window.ckeditorInstances[textarea.id].getData();
                textarea.value = content;
            } catch (e) {
                // Silent fail
            }
        }
    });
    
    // Also sync instruction editors
    document.querySelectorAll('.instruksi-editor').forEach(function(textarea) {
        if (window.ckeditorInstances && window.ckeditorInstances[textarea.id]) {
            try {
                const content = window.ckeditorInstances[textarea.id].getData();
                textarea.value = content;
            } catch (e) {
                // Silent fail
            }
        }
    });
    
    if (!isValid) {
        alert(errorMessage || 'Semua field wajib diisi untuk setiap assessment.');
        return false;
    }
    
    // Jika valid, pastikan semua field penilaian_id terkirim
    // Field yang di-disable tidak terkirim, jadi kita perlu enable dulu
    assessments.forEach((assessment, index) => {
        const penilaianSelect = assessment.querySelector('select[name*="[penilaian_id]"]');
        
        if (penilaianSelect) {
            // Pastikan select tidak di-disable
            penilaianSelect.disabled = false;
            penilaianSelect.removeAttribute('disabled');
            
            // Jika value masih kosong, skip assessment ini dengan menghapus dari form
            if (!penilaianSelect.value || penilaianSelect.value === '') {
                console.warn('Assessment', index, 'has no penilaian_id after processing, removing from form');
                assessment.remove();
            }
        }
    });
    
    // Jika valid, submit form secara programmatic
    // Tidak perlu set required kembali karena form sudah menggunakan novalidate
    // Submit form
    this.submit();
});

// Add event listeners for select changes
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.includes('[penilaian_id]')) {
        updateAvailableOptions();
        checkAndUpdateAddButton();
        togglePdfUpload(e.target);
        
        // Pastikan model_in_tray diupdate saat assessment berubah
        const selectedOption = e.target.options[e.target.selectedIndex];
        if (selectedOption && selectedOption.dataset.jenis === 'in_tray') {
            const assessmentItem = e.target.closest('.assessment-item');
            const intrayModelSection = assessmentItem.querySelector('.intray-model-section');
            if (intrayModelSection) {
                const modelSelect = intrayModelSection.querySelector('select[name*="[model_in_tray]"]');
                if (modelSelect) {
                    // Jangan set default value, biarkan user memilih atau gunakan nilai yang sudah ada
                }
            }
        }
    }
    
    
    // Update status tampilan file saat memilih file
    if (e.target.type === 'file' && e.target.name && e.target.name.includes('[file_pdf]')) {
        const assessmentItem = e.target.closest('.assessment-item');
        const pdfSection = assessmentItem ? assessmentItem.querySelector('.pdf-upload-section') : null;
        const statusDiv = pdfSection ? pdfSection.querySelector('.pdf-status') : e.target.parentElement.querySelector('.pdf-status');
        const currentPdfDisplay = pdfSection ? pdfSection.querySelector('.current-pdf-display') : null;
        const currentPdfName = currentPdfDisplay ? currentPdfDisplay.querySelector('.current-pdf-name') : null;
        
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            // Validasi file size dan type
            if (file.size > 10 * 1024 * 1024) {
                if (statusDiv) {
                    statusDiv.textContent = 'Error: File terlalu besar (max 10MB)';
                    statusDiv.className = 'pdf-status text-sm text-red-600';
                }
                if (currentPdfDisplay) {
                    currentPdfDisplay.style.display = 'none';
                }
                e.target.value = '';
                return;
            }
            if (file.type !== 'application/pdf') {
                if (statusDiv) {
                    statusDiv.textContent = 'Error: Hanya file PDF yang diperbolehkan';
                    statusDiv.className = 'pdf-status text-sm text-red-600';
                }
                if (currentPdfDisplay) {
                    currentPdfDisplay.style.display = 'none';
                }
                e.target.value = '';
                return;
            }
            
            // Tampilkan Current PDF Display jika ada
            if (currentPdfDisplay && currentPdfName) {
                currentPdfDisplay.style.display = 'block';
                currentPdfName.textContent = file.name;
                // Sembunyikan status text karena sudah ada section Current PDF Display
                if (statusDiv) {
                    statusDiv.textContent = '';
                }
            } else if (statusDiv) {
                // Fallback: tampilkan status text jika section tidak ditemukan
                statusDiv.textContent = `File dipilih: ${file.name}`;
                statusDiv.className = 'pdf-status text-sm text-green-600';
            }
        } else {
            if (statusDiv) {
                statusDiv.textContent = '';
                statusDiv.className = 'pdf-status text-sm text-tertiary';
            }
            if (currentPdfDisplay) {
                currentPdfDisplay.style.display = 'none';
            }
        }
    }
});

// Function to toggle PDF upload section based on assessment type
function togglePdfUpload(selectElement) {
    const assessmentItem = selectElement.closest('.assessment-item');
    const pdfSection = assessmentItem.querySelector('.pdf-upload-section');
    const memoSection = assessmentItem.querySelector('.memo-section');
    const intrayModelSection = assessmentItem.querySelector('.intray-model-section');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    
    // PDF upload tersedia untuk studi_kasus, roleplay, fgd, dan in_tray
    if (selectedOption && (selectedOption.dataset.jenis === 'studi_kasus' || 
                          selectedOption.dataset.jenis === 'roleplay' || 
                          selectedOption.dataset.jenis === 'fgd' ||
                          selectedOption.dataset.jenis === 'in_tray')) {
        pdfSection.style.display = 'block';
        // Check if there's existing PDF
        checkExistingPdf(assessmentItem, selectedOption.value, selectedOption.dataset.file);
        
        // Update label berdasarkan jenis assessment
        const pdfLabel = pdfSection.querySelector('.flex.items-center.mb-3 label');
        if (pdfLabel) {
            if (selectedOption.dataset.jenis === 'studi_kasus') {
                pdfLabel.textContent = 'Upload PDF Studi Kasus';
            } else if (selectedOption.dataset.jenis === 'roleplay') {
                pdfLabel.textContent = 'Upload PDF Role-Play';
            } else if (selectedOption.dataset.jenis === 'fgd') {
                pdfLabel.textContent = 'Upload PDF LGD/FGD';
            } else if (selectedOption.dataset.jenis === 'in_tray') {
                pdfLabel.textContent = 'Upload PDF In-Tray Exercise';
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
            } else if (selectedOption.dataset.jenis === 'in_tray') {
                pdfDescription.textContent = 'Upload file PDF untuk materi dan instruksi in-tray exercise (max 10MB)';
            }
        }
        
        // Tampilkan section memo dan model in-tray jika jenis adalah in_tray
        if (selectedOption.dataset.jenis === 'in_tray') {
            if (memoSection) memoSection.style.display = 'block';
            if (intrayModelSection) intrayModelSection.style.display = 'block';
        } else {
            if (memoSection) memoSection.style.display = 'none';
            if (intrayModelSection) intrayModelSection.style.display = 'none';
        }
    } else {
        pdfSection.style.display = 'none';
        // Clear PDF input when hiding
        const pdfInput = pdfSection.querySelector('input[type="file"]');
        if (pdfInput) {
            pdfInput.value = '';
        }
        // Hide memo and intray model sections
        if (memoSection) memoSection.style.display = 'none';
        if (intrayModelSection) intrayModelSection.style.display = 'none';
    }
}

// Function to check existing PDF for assessment
function checkExistingPdf(assessmentItem, penilaianId, existingFile) {
    const pdfSection = assessmentItem.querySelector('.pdf-upload-section');
    const currentPdfDisplay = pdfSection ? pdfSection.querySelector('.current-pdf-display') : assessmentItem.querySelector('.current-pdf-display');
    const currentPdfName = currentPdfDisplay ? currentPdfDisplay.querySelector('.current-pdf-name') : null;
    const isExistingAssessment = assessmentItem && assessmentItem.dataset && assessmentItem.dataset.existing === 'true';
    const fileToShow = isExistingAssessment ? existingFile : '';

    if (fileToShow && currentPdfDisplay && currentPdfName) {
        currentPdfDisplay.style.display = 'block';
        currentPdfName.textContent = fileToShow.split('/').pop();
    } else if (currentPdfDisplay) {
        currentPdfDisplay.style.display = 'none';
        if (currentPdfName) currentPdfName.textContent = '';
    }
}

// Function to handle PDF upload
function handlePdfUpload(inputElement, index) {
    const file = inputElement.files[0];
    const assessmentItem = inputElement.closest('.assessment-item');
    if (!assessmentItem) return;
    
    const pdfSection = assessmentItem.querySelector('.pdf-upload-section');
    const statusDiv = pdfSection ? pdfSection.querySelector('.pdf-status') : inputElement.parentElement.querySelector('.pdf-status');
    const currentPdfDisplay = pdfSection ? pdfSection.querySelector('.current-pdf-display') : assessmentItem.querySelector('.current-pdf-display');
    const currentPdfName = currentPdfDisplay ? currentPdfDisplay.querySelector('.current-pdf-name') : null;
    
    if (file) {
        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            if (statusDiv) {
                statusDiv.textContent = 'Error: File terlalu besar (max 10MB)';
                statusDiv.className = 'pdf-status text-sm text-red-600';
            }
            inputElement.value = '';
            // Hide current PDF display if error
            if (currentPdfDisplay) {
                currentPdfDisplay.style.display = 'none';
            }
            return;
        }
        
        // Validate file type
        if (file.type !== 'application/pdf') {
            if (statusDiv) {
                statusDiv.textContent = 'Error: Hanya file PDF yang diperbolehkan';
                statusDiv.className = 'pdf-status text-sm text-red-600';
            }
            inputElement.value = '';
            // Hide current PDF display if error
            if (currentPdfDisplay) {
                currentPdfDisplay.style.display = 'none';
            }
            return;
        }
        
        // Tampilkan current PDF display dengan nama file yang dipilih
        if (currentPdfDisplay && currentPdfName) {
            currentPdfDisplay.style.display = 'block';
            currentPdfName.textContent = file.name;
            // Sembunyikan status text karena sudah ada section Current PDF Display
            if (statusDiv) {
                statusDiv.textContent = '';
            }
        } else {
            // Fallback: tampilkan status text jika section tidak ditemukan
            if (statusDiv) {
                statusDiv.textContent = `File dipilih: ${file.name}`;
                statusDiv.className = 'pdf-status text-sm text-green-600';
            }
        }
    } else {
        if (statusDiv) {
            statusDiv.textContent = '';
        }
        // Hide current PDF display jika tidak ada file
        if (currentPdfDisplay) {
            currentPdfDisplay.style.display = 'none';
        }
    }
}

// PDF Preview functionality
function previewCurrentPdf(index) {
    const assessmentItem = document.querySelector(`[data-assessment-index="${index}"]`) || 
                          document.querySelectorAll('.assessment-item')[index];
    
    if (!assessmentItem) {
        return;
    }
    
    const isExistingAssessment = assessmentItem.dataset && assessmentItem.dataset.existing === 'true';
    const selectElement = assessmentItem.querySelector('select[name*="[penilaian_id]"]');
    if (!selectElement) {
        return;
    }
    
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const pdfFile = (isExistingAssessment && selectedOption) ? selectedOption.dataset.file : '';

    if (!pdfFile) {
        alert('Tidak ada PDF yang tersedia untuk di-preview');
        return;
    }
    
    const pdfUrl = selectedOption.dataset.url || adminAssessmentPdfUrl(selectedOption.value);

    if (!pdfUrl) {
        alert('URL PDF tidak tersedia');
        return;
    }

    adminShowPdfPreview({
        modalId: 'pdfPreviewModal',
        contentId: 'pdfPreviewContent',
        openTabId: 'pdfPreviewOpenTab',
        pdfUrl: pdfUrl,
    });
}

function closePdfPreview() {
    adminClosePdfPreview('pdfPreviewModal');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('pdfPreviewModal');
    if (modal && e.target === modal) {
        closePdfPreview();
    }
});

// Toast Notification util (selaras dengan index)
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
            return;
        }
        
        const pdfSection = assessmentItem.querySelector('.pdf-upload-section');
        const currentPdfDisplay = pdfSection ? pdfSection.querySelector('.current-pdf-display') : assessmentItem.querySelector('.current-pdf-display');
        const currentPdfName = currentPdfDisplay ? currentPdfDisplay.querySelector('.current-pdf-name') : null;
        
        if (currentPdfDisplay) {
            currentPdfDisplay.style.display = 'none';
        }
        
        if (currentPdfName) {
            currentPdfName.textContent = '';
        }
        
        // Clear any file input
        const pdfInput = pdfSection ? pdfSection.querySelector('input[type="file"]') : assessmentItem.querySelector('input[type="file"]');
        if (pdfInput) {
            pdfInput.value = '';
        }
        
        // Clear status text
        const statusDiv = pdfSection ? pdfSection.querySelector('.pdf-status') : assessmentItem.querySelector('.pdf-status');
        if (statusDiv) {
            statusDiv.textContent = '';
            statusDiv.className = 'pdf-status text-sm text-tertiary';
        }
        
        // Show success message
        showNotification('PDF berhasil dihapus', 'success');
    }
}

// Memo handling for edit page
function addMemo(button) {
    const section = button.closest('.memo-section');
    const container = section.querySelector('.memo-container');
    const memoIndex = container.children.length;
    const wrapper = document.createElement('div');
    wrapper.className = 'border border-gray-200 rounded p-3 bg-white';
    wrapper.innerHTML = `
        <div class=\"flex justify-between items-center mb-2\"> 
            <span class=\"text-sm text-tertiary\">Memo ${memoIndex + 1}</span>
            <button type=\"button\" class=\"text-red-600 hover:text-red-800 text-xs\" onclick=\"this.closest('div.border').remove()\">Hapus</button>
        </div>
        <textarea name=\"assessments[INDEX][memos][]\" id=\"memo-editor-INDEX-${memoIndex}\" class=\"memo-editor w-full\" rows=\"10\" placeholder=\"Tulis isi memo di sini...\"></textarea>
    `;
    wrapper.querySelectorAll('textarea[name*="assessments[INDEX]"]').forEach(el => {
        el.name = el.name.replace('INDEX', getAssessmentIndexFromElement(section.closest('.assessment-item')));
    });
    wrapper.querySelectorAll('textarea[id*="INDEX"]').forEach(el => {
        el.id = el.id.replace('INDEX', getAssessmentIndexFromElement(section.closest('.assessment-item')));
    });
    container.appendChild(wrapper);
    setTimeout(function() {
        if (window.initCKEditor && window.$) {
            const textarea = wrapper.querySelector('textarea');
            // Force destroy any existing instance
            if (window.ckeditorInstances && window.ckeditorInstances[textarea.id]) {
                try {
                    $('#' + textarea.id).summernote('destroy');
                    delete window.ckeditorInstances[textarea.id];
                } catch (e) {
                    // Silent fail
                }
            }
            // Initialize new instance
            try {
                window.initCKEditor(textarea.id);
            } catch (e) {
                // Silent fail
            }
        } else {
            setTimeout(arguments.callee, 200);
        }
    }, 500);
}

function getAssessmentIndexFromElement(item) {
    const anyInput = item.querySelector('input[name*="assessments["]') || item.querySelector('select[name*="assessments["]') || item.querySelector('textarea[name*="assessments["]');
    if (!anyInput) return 0;
    const match = anyInput.name.match(/assessments\[(\d+)\]/);
    return match ? match[1] : 0;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Form submitted
        });
    }
});
</script>
@endsection
