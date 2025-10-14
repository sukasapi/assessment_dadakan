@extends('peserta.layouts.app')

@section('title', 'Halaman Studi Kasus')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Halaman Studi Kasus</h1>
            <p class="text-gray-600 mt-2">{{ $assessment->nama }}</p>
        </div>

        <!-- Tombol Kembali -->
        <div class="flex justify-start mb-2">
            <a href="{{ route('peserta.dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                ← Kembali ke Dashboard
            </a>
        </div>

        <!-- Form Assessment -->
        <form action="{{ route('peserta.assessment.studi-kasus.store', $assessment->id) }}" method="POST" enctype="multipart/form-data" id="assessmentForm">
            @csrf
            <input type="hidden" name="sesi" value="{{ request('sesi', $assessment->sesi_penilaian_id) }}">
            <div class="space-y-8">
                
                <!-- Petunjuk Pengisian -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Petunjuk Pengerjaan Studi Kasus:</h2>
                    <div class="prose max-w-none">
                        @if($sesiAssessment && !empty(trim($sesiAssessment->instruksi_khusus)))
                            {!! $sesiAssessment->instruksi_khusus !!}
                        @elseif($assessment->petunjuk)
                            {!! $assessment->petunjuk !!}
                        @else
                            <p class="text-gray-500 italic">Petunjuk pengisian belum tersedia.</p>
                        @endif
                    </div>
                </div>

                <!-- Deskripsi Soal -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    
                    @if($assessment->file_pdf)
                        <!-- PDF untuk Studi Kasus -->
                        @php
                            $pdfUrl = route('assessment.pdf.view', ['penilaianId' => $assessment->id, 'filename' => $assessment->file_pdf]);
                        @endphp
                        <div class="w-full border rounded-md overflow-hidden">
                            <iframe 
                                style="width: 100%; height: 500px; border: 1px solid #eeeeee;" 
                                src="{{ $pdfUrl }}#toolbar=0&navpanes=0&scrollbar=0&view=Fit" 
                                width="100%" 
                                height="500" 
                                frameborder="0" 
                                allowfullscreen="allowfullscreen">
                            </iframe>
                        </div>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const container = document.getElementById('studiKasusPdfViewer');
                            const originalUrl = "{{ $pdfUrl }}";
                            
                            // Set PDF.js worker
                            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                            
                            // Disable right-click context menu
                            container.addEventListener('contextmenu', function(e) {
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
                            fetch(originalUrl)
                                .then(response => response.blob())
                                .then(blob => {
                                    const blobUrl = URL.createObjectURL(blob);
                                    
                                    // Load PDF using PDF.js with blob URL
                                    return pdfjsLib.getDocument(blobUrl).promise;
                                })
                                .then(function(pdf) {
                                    // Create canvas for first page
                                    const canvas = document.createElement('canvas');
                                    const context = canvas.getContext('2d');
                                    
                                    let currentPage = 1;
                                    let currentScale = 1.0;
                                    
                                    // Render page function
                                    function renderPage(pageNum) {
                                        pdf.getPage(pageNum).then(function(page) {
                                            const viewport = page.getViewport({ scale: currentScale });
                                            canvas.height = viewport.height;
                                            canvas.width = viewport.width;
                                            
                                            const renderContext = {
                                                canvasContext: context,
                                                viewport: viewport
                                            };
                                            
                                            page.render(renderContext);
                                        });
                                    }
                                    
                                    // Initialize with first page
                                    pdf.getPage(1).then(function(page) {
                                        // Set initial scale to fit width
                                        const containerWidth = container.clientWidth - 80; // Account for padding
                                        const pageViewport = page.getViewport({ scale: 1.0 });
                                        currentScale = Math.min(containerWidth / pageViewport.width, 1.5);
                                        currentScale = Math.max(currentScale, 0.5);
                                        
                                        renderPage(1);
                                        
                                        container.innerHTML = '';
                                        
                                        // Create PDF viewer container
                                        const pdfViewer = document.createElement('div');
                                        pdfViewer.className = 'w-full bg-white border rounded-lg shadow-lg overflow-hidden';
                                        
                                        // Create top toolbar
                                        const topToolbar = document.createElement('div');
                                        topToolbar.className = 'flex justify-between items-center p-3 bg-gray-800 text-white';
                                        
                                        // Left side - Zoom controls
                                        const zoomControls = document.createElement('div');
                                        zoomControls.className = 'flex items-center gap-2';
                                        
                                        const zoomOutBtn = document.createElement('button');
                                        zoomOutBtn.innerHTML = '−';
                                        zoomOutBtn.className = 'w-8 h-8 bg-gray-600 hover:bg-gray-500 rounded flex items-center justify-center text-lg font-bold';
                                        
                                        const zoomInBtn = document.createElement('button');
                                        zoomInBtn.innerHTML = '+';
                                        zoomInBtn.className = 'w-8 h-8 bg-gray-600 hover:bg-gray-500 rounded flex items-center justify-center text-lg font-bold';
                                        
                                        const zoomInfo = document.createElement('span');
                                        zoomInfo.textContent = `${Math.round(currentScale * 100)}%`;
                                        zoomInfo.className = 'text-sm px-2';
                                        
                                        zoomControls.appendChild(zoomOutBtn);
                                        zoomControls.appendChild(zoomInfo);
                                        zoomControls.appendChild(zoomInBtn);
                                        
                                        // Right side - Page info
                                        const pageInfo = document.createElement('div');
                                        pageInfo.className = 'flex items-center gap-2';
                                        
                                        const pageText = document.createElement('span');
                                        pageText.textContent = `${currentPage} of ${pdf.numPages}`;
                                        pageText.className = 'text-sm';
                                        
                                        pageInfo.appendChild(pageText);
                                        
                                        topToolbar.appendChild(zoomControls);
                                        topToolbar.appendChild(pageInfo);
                                        
                                        // Create PDF content area
                                        const pdfContent = document.createElement('div');
                                        pdfContent.className = 'p-4 bg-gray-100 flex justify-center overflow-auto';
                                        pdfContent.style.maxHeight = '70vh';
                                        pdfContent.appendChild(canvas);
                                        
                                        // Create bottom toolbar
                                        const bottomToolbar = document.createElement('div');
                                        bottomToolbar.className = 'flex justify-center items-center p-3 bg-gray-800 text-white';
                                        
                                        const prevBtn = document.createElement('button');
                                        prevBtn.innerHTML = '←';
                                        prevBtn.className = 'w-10 h-10 bg-gray-600 hover:bg-gray-500 rounded flex items-center justify-center text-lg font-bold';
                                        
                                        const nextBtn = document.createElement('button');
                                        nextBtn.innerHTML = '→';
                                        nextBtn.className = 'w-10 h-10 bg-gray-600 hover:bg-gray-500 rounded flex items-center justify-center text-lg font-bold';
                                        
                                        bottomToolbar.appendChild(prevBtn);
                                        bottomToolbar.appendChild(nextBtn);
                                        
                                        // Assemble PDF viewer
                                        pdfViewer.appendChild(topToolbar);
                                        pdfViewer.appendChild(pdfContent);
                                        pdfViewer.appendChild(bottomToolbar);
                                        
                                        container.appendChild(pdfViewer);
                                        
                                        // Event handlers
                                        zoomOutBtn.onclick = () => {
                                            currentScale = Math.max(0.3, currentScale - 0.2);
                                            renderPage(currentPage);
                                            zoomInfo.textContent = `${Math.round(currentScale * 100)}%`;
                                        };
                                        
                                        zoomInBtn.onclick = () => {
                                            currentScale = Math.min(3.0, currentScale + 0.2);
                                            renderPage(currentPage);
                                            zoomInfo.textContent = `${Math.round(currentScale * 100)}%`;
                                        };
                                        
                                        prevBtn.onclick = () => {
                                            if (currentPage > 1) {
                                                currentPage--;
                                                renderPage(currentPage);
                                                pageText.textContent = `${currentPage} of ${pdf.numPages}`;
                                            }
                                        };
                                        
                                        nextBtn.onclick = () => {
                                            if (currentPage < pdf.numPages) {
                                                currentPage++;
                                                renderPage(currentPage);
                                                pageText.textContent = `${currentPage} of ${pdf.numPages}`;
                                            }
                                        };
                                            
                                        });
                                    });
                                }).catch(function(error) {
                                    console.error('Error loading PDF:', error);
                                    container.innerHTML = '<div class="text-center text-red-600 p-8"><p>Gagal memuat PDF. Silakan refresh halaman.</p></div>';
                                });
                            }).catch(function(error) {
                                console.error('Error fetching PDF:', error);
                                container.innerHTML = '<div class="text-center text-red-600 p-8"><p>Gagal mengambil PDF. Silakan refresh halaman.</p></div>';
                            });
                            
                            // Cleanup blob URL when page unloads
                            window.addEventListener('beforeunload', function() {
                                if (typeof blobUrl !== 'undefined') {
                                    URL.revokeObjectURL(blobUrl);
                                }
                            });
                        });
                        </script>
                    @else
                        <div class="flex flex-col items-center justify-center h-32 text-gray-500">
                            <p class="text-lg font-medium">Deskripsi soal belum tersedia</p>
                            <p class="text-sm">Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                        </div>
                    @endif
                </div>

                <!-- Jawaban Peserta -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Jawaban Anda</h2>
                    <div class="space-y-4">
                        <textarea 
                            name="jawaban" 
                            id="jawaban" 
                            rows="8" 
                            class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="Tuliskan jawaban Anda untuk studi kasus ini di sini..."
                        >{{ old('jawaban', $existingJawaban ?? '') }}</textarea>
                        
                        @error('jawaban')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-4 flex gap-3">
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        onclick="setAction('draft')"
                    >
                        Simpan Sementara
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        onclick="setAction('final')"
                    >
                        Simpan Final
                    </button>
                    
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-6">
                
                   
                </div>

                <!-- Hidden input untuk action -->
                <input type="hidden" name="assessment_action" id="assessmentAction" value="draft">
            </div>
        </form>
        
        @if($sesiAssessment && (int) $sesiAssessment->durasi_default > 0)
            <div class="mt-8 bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sisa Waktu</h3>
                    <div class="text-4xl font-bold text-green-600 mb-2" id="timer">
                        {{ (int) $sesiAssessment->durasi_default }}:00
                    </div>
                    <p class="text-sm text-gray-500">Gunakan waktu dengan bijak untuk menyelesaikan assessment ini</p>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.ck-editor__editable[role="textbox"] { min-height: 12rem; }
.ck-content ul { 
    list-style: disc !important; 
    list-style-position: outside !important; 
    margin-left: 1.5rem !important; 
    padding-left: 0 !important; 
}
.ck-content ol { 
    list-style: decimal !important; 
    list-style-position: outside !important; 
    margin-left: 1.5rem !important; 
    padding-left: 0 !important; 
}
.ck-content li {
    display: list-item !important;
    margin: 0.25rem 0 !important;
}

/* Styling untuk instruksi khusus HTML content */
.prose ul { 
    list-style: disc !important; 
    list-style-position: outside !important; 
    margin-left: 1.5rem !important; 
    padding-left: 0 !important; 
    margin-top: 0.5rem !important;
    margin-bottom: 0.5rem !important;
}
.prose ol { 
    list-style: decimal !important; 
    list-style-position: outside !important; 
    margin-left: 1.5rem !important; 
    padding-left: 0 !important; 
    margin-top: 0.5rem !important;
    margin-bottom: 0.5rem !important;
}
.prose li {
    display: list-item !important;
    margin: 0.25rem 0 !important;
    padding-left: 0.25rem !important;
}

/* Fix untuk bullet dan numbering yang tidak muncul */
.ck-editor__editable ul li::marker,
.ck-editor__editable ol li::marker {
    display: block !important;
    visibility: visible !important;
}
</style>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
// Util Popup global (auto-close 3 detik, opsi redirect dashboard)
const DASHBOARD_URL = "{{ route('peserta.dashboard') }}";
function showPopup(message, type = 'success', redirectToDashboard = false) {
    let modal = document.getElementById('globalPopup');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'globalPopup';
        modal.className = 'fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden';
        modal.innerHTML = `
            <div class=\"bg-white w-11/12 md:w-2/3 lg:w-1/3 rounded-lg shadow p-5 border\">\n                <div id=\"globalPopupContent\" class=\"text-center text-gray-800\"></div>\n            </div>`;
        document.body.appendChild(modal);
    }
    const color = type === 'success' ? 'text-green-700' : 'text-red-700';
    const content = document.getElementById('globalPopupContent');
    if (content) content.innerHTML = `<p class="${color} text-sm">${message}</p>`;
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('hidden');
        if (redirectToDashboard) {
            window.location.href = DASHBOARD_URL;
        }
    }, 3000);
}
function setAction(action) {
    document.getElementById('assessmentAction').value = action;
}

// Inisialisasi CKEditor 5 Classic basic untuk jawaban studi kasus
let jawabanEditor = null;
ClassicEditor.create(document.getElementById('jawaban'), {
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
}).then(ed => { jawabanEditor = ed; }).catch(err => console.error(err));

// Auto-save draft setiap 30 detik (ambil dari ClassicEditor)
let autoSaveTimer;
const formEl = document.getElementById('assessmentForm');
let isSubmitting = false;
let hasChanges = false;

function scheduleAutoSave() {
    hasChanges = true;
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(function() {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('jawaban', jawabanEditor ? jawabanEditor.getData() : (document.getElementById('jawaban')?.value || ''));
        formData.append('assessment_action', 'draft');
        const sesiValAuto = document.querySelector('input[name="sesi"]')?.value || '';
        formData.append('sesi', sesiValAuto);
        fetch("{{ route('peserta.assessment.studi-kasus.store', $assessment->id) }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        }).then(r => r.json()).then(() => {}).catch(() => {});
    }, 30000);
}

// Trigger autosave on editor change
setTimeout(() => {
    if (jawabanEditor) {
        jawabanEditor.model.document.on('change:data', scheduleAutoSave);
    }
}, 300);

// Konfirmasi sebelum meninggalkan halaman jika ada perubahan
window.addEventListener('beforeunload', function(e) {
    if (isSubmitting) {
        return;
    }
    const currentVal = jawabanEditor ? jawabanEditor.getData() : (document.getElementById('jawaban')?.value || '');
    if (hasChanges && currentVal.trim() !== `{{ old('jawaban', $existingJawaban ?? '') }}`.trim()) {
        e.preventDefault();
        e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
    }
});

if (formEl) {
    formEl.addEventListener('submit', async function(e) {
        const action = document.getElementById('assessmentAction')?.value || 'draft';
        if (jawabanEditor) {
            document.getElementById('jawaban').value = jawabanEditor.getData();
        }
        const val = (jawabanEditor ? jawabanEditor.getData() : document.getElementById('jawaban').value || '').trim();
        if (!val) {
            e.preventDefault();
            showPopup('Jawaban belum diisi.', 'error', false);
            return;
        }
        // pastikan field sesi ikut terkirim
        const sesiHidden = document.querySelector('input[name="sesi"]');
        if (!sesiHidden) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sesi';
            input.value = new URLSearchParams(window.location.search).get('sesi') || '';
            formEl.appendChild(input);
        }
        // TANDAI SEDANG SUBMIT (baik draft maupun final) supaya beforeunload tidak muncul
        isSubmitting = true;
        clearTimeout(autoSaveTimer);
        if (action !== 'final') {
            return; // submit normal untuk draft
        }
        // FINAL: kirim AJAX, lalu redirect ke dashboard saat sukses
        e.preventDefault();
        try {
            const formData = new FormData(formEl);
            formData.set('assessment_action', 'final');
            const res = await fetch(formEl.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData,
                credentials: 'same-origin'
            });
            if (!res.ok) {
                let msg = 'Gagal menyimpan final.';
                try { const data = await res.json(); if (data && data.message) msg = data.message; } catch (_) {}
                showPopup(msg, 'error', false);
                isSubmitting = false;
                return;
            }
            showPopup('Simpan final berhasil. Mengarahkan ke dashboard...', 'success', true);
        } catch (err) {
            showPopup('Terjadi kesalahan jaringan. Coba lagi.', 'error', false);
            isSubmitting = false;
        }
    });
}

// Countdown timer
var remainingTime = parseInt("{{ $sesiAssessment && (int) $sesiAssessment->durasi_default > 0 ? (int) $sesiAssessment->durasi_default * 60 : 0 }}", 10) || 0;
if (remainingTime > 0) {
    function updateTimer() {
        if (remainingTime <= 0) {
            const t = document.getElementById('timer');
            if (t) t.textContent = '00:00';
            return;
        }
        var minutes = Math.floor(remainingTime / 60);
        var seconds = remainingTime % 60;
        const t = document.getElementById('timer');
        if (t) t.textContent = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
        remainingTime--;
    }
    updateTimer();
    setInterval(updateTimer, 1000);
}
</script>
@endsection
