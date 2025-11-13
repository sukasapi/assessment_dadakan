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
                                style="width: 100%; height: 600px; border: 1px solid #eeeeee;" 
                                src="{{ $pdfUrl }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH" 
                                width="100%" 
                                height="600" 
                                frameborder="0" 
                                allowfullscreen="allowfullscreen">
                            </iframe>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-32 text-gray-500">
                            <p class="text-lg font-medium">Deskripsi soal belum tersedia</p>
                            <p class="text-sm">Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                        </div>
                    @endif
                </div>

                <!-- Jawaban Peserta -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Jawaban Anda</h2>
                        @if(isset($jawabanStatus) && $jawabanStatus === 'final')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                ✓ Status: Final - Sudah Disimpan Final
                            </span>
                        @elseif(isset($statusKemajuan) && $statusKemajuan === 'selesai')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                ✓ Status: Final - Sudah Disimpan Final
                            </span>
                        @elseif(isset($jawabanStatus) && $jawabanStatus === 'draft')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                ⏳ Status: Draft - Belum Final
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                Belum Ada Jawaban
                            </span>
                        @endif
                    </div>
                    <div class="space-y-4">
                        @php
                            $isFinal = (isset($jawabanStatus) && $jawabanStatus === 'final') || (isset($statusKemajuan) && $statusKemajuan === 'selesai');
                            $disabledAttr = $isFinal ? 'disabled' : '';
                            $disabledClass = $isFinal ? 'opacity-60 cursor-not-allowed bg-gray-50' : '';
                        @endphp
                        <textarea 
                            name="jawaban" 
                            id="jawaban" 
                            rows="8" 
                            class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none {{ $disabledClass }}"
                            placeholder="Tuliskan jawaban Anda untuk studi kasus ini di sini..."
                            {{ $disabledAttr }}
                        >{{ old('jawaban', $existingJawaban ?? '') }}</textarea>
                        
                        @error('jawaban')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-4 flex gap-3">
                        @php
                            $buttonDisabledClass = $isFinal ? 'opacity-50 cursor-not-allowed' : '';
                        @endphp
                        <button 
                            type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $buttonDisabledClass }}"
                            onclick="setAction('draft')"
                            {{ $disabledAttr }}
                        >
                            Simpan Sementara
                        </button>
                        <button 
                            type="submit" 
                            class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 {{ $buttonDisabledClass }}"
                            onclick="setAction('final')"
                            {{ $disabledAttr }}
                        >
                            Simpan Final
                        </button>
                    </div>
                </div>

                <!-- Info Penilaian & Tombol Lihat Penilaian -->
                @if(isset($penilaian) && $penilaian && $penilaian->status === 'final')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-800 font-medium">
                                    ✓ Jawaban Anda sudah dinilai oleh admin
                                </p>
                                <p class="text-xs text-blue-600 mt-1">
                                    Status: Final
                                </p>
                            </div>
                            <button 
                                type="button" 
                                id="btnLihatPenilaian"
                                data-penilaian-id="{{ $penilaian->penilaian_id }}"
                                data-sesi-id="{{ request('sesi', $assessment->sesi_penilaian_id) }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                            >
                                👁️ Lihat Penilaian
                            </button>
                        </div>
                    </div>
                @endif

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
const jawabanTextarea = document.getElementById('jawaban');
const isDisabled = jawabanTextarea && jawabanTextarea.hasAttribute('disabled');

if (jawabanTextarea && !isDisabled) {
    ClassicEditor.create(jawabanTextarea, {
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
    }).then(ed => { 
        jawabanEditor = ed;
    }).catch(err => console.error(err));
} else if (isDisabled) {
    // Jika disabled, tidak perlu inisialisasi editor
    console.log('Textarea disabled, skipping CKEditor initialization');
}

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

// Trigger autosave on editor change (hanya jika tidak disabled)
setTimeout(() => {
    if (jawabanEditor && !isDisabled) {
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
        // Cek apakah form disabled (status final)
        const jawabanTextarea = document.getElementById('jawaban');
        if (jawabanTextarea && jawabanTextarea.hasAttribute('disabled')) {
            e.preventDefault();
            showPopup('Jawaban sudah disimpan sebagai final. Tidak dapat diubah lagi.', 'error', false);
            return;
        }
        
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
<!-- Modal Lihat Penilaian -->
<div id="modalPenilaian" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Hasil Penilaian</h3>
                <button onclick="closeModalPenilaian()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="penilaianContent" class="text-sm text-gray-700">
                <!-- Content will be loaded here -->
                <div class="flex items-center justify-center py-8">
                    <div class="text-gray-500">Memuat penilaian...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle tombol Lihat Penilaian
document.addEventListener('DOMContentLoaded', function() {
    const btnLihatPenilaian = document.getElementById('btnLihatPenilaian');
    if (btnLihatPenilaian) {
        btnLihatPenilaian.addEventListener('click', function() {
            const penilaianId = this.getAttribute('data-penilaian-id');
            const sesiId = this.getAttribute('data-sesi-id');
            loadPenilaian(penilaianId, sesiId);
        });
    }
});

function loadPenilaian(penilaianId, sesiId) {
    const modal = document.getElementById('modalPenilaian');
    const content = document.getElementById('penilaianContent');
    
    // Show modal
    modal.classList.remove('hidden');
    content.innerHTML = '<div class="flex items-center justify-center py-8"><div class="text-gray-500">Memuat penilaian...</div></div>';
    
    // Fetch data
    const url = '{{ route("peserta.penilaian.studi-kasus", ":id") }}'.replace(':id', penilaianId) + '?sesi_id=' + sesiId;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderPenilaian(data.data);
        } else {
            content.innerHTML = '<p class="text-red-500">Error: ' + (data.message || 'Gagal memuat penilaian') + '</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        content.innerHTML = '<p class="text-red-500">Terjadi kesalahan saat memuat penilaian.</p>';
    });
}

function renderPenilaian(data) {
    const content = document.getElementById('penilaianContent');
    
    // Map jawaban untuk display
    const jawabanText = {
        'ya': 'Ya',
        'tidak': 'Tidak'
    };
    
    // Map pertanyaan
    const pertanyaan = [
        'Apakah jawaban sudah menjawab pertanyaan soal?',
        'Apakah jawaban sudah mencerminkan kompetensi-kompetensi?',
        'Apakah jawaban sudah menggunakan alat analisis?'
    ];
    
    let html = '<div class="space-y-4">';
    html += '<h4 class="font-medium text-gray-900 mb-4">Penilaian:</h4>';
    
    // 3 Pertanyaan
    for (let i = 1; i <= 3; i++) {
        const jawaban = data['pertanyaan_' + i];
        const jawabanLabel = jawabanText[jawaban] || jawaban;
        const bgColor = jawaban === 'ya' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        
        html += '<div class="space-y-2">';
        html += '<label class="block text-sm font-medium text-gray-700">' + (i) + '. ' + pertanyaan[i-1] + '</label>';
        html += '<div class="text-sm">';
        html += '<span class="px-3 py-1 rounded ' + bgColor + '">';
        html += jawabanLabel;
        html += '</span>';
        html += '</div>';
        html += '</div>';
    }
    
    // Catatan
    html += '<div class="space-y-2 pt-4 border-t">';
    html += '<label class="block text-sm font-medium text-gray-700">Catatan:</label>';
    html += '<div class="bg-gray-50 p-4 rounded-md border border-gray-200">';
    html += '<div class="text-sm text-gray-700">' + (data.catatan || '<em class="text-gray-400">Tidak ada catatan</em>') + '</div>';
    html += '</div>';
    html += '</div>';
    
    html += '</div>';
    
    content.innerHTML = html;
}

function closeModalPenilaian() {
    document.getElementById('modalPenilaian').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('modalPenilaian').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalPenilaian();
    }
});
</script>

@endsection
