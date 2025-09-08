@extends('peserta.layouts.app')

@section('title', 'Pengerjaan Assessment')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pengerjaan Assessment</h1>
            <p class="text-gray-600 mt-2">{{ $assessment->nama }}</p>
        </div>

        <div class="space-y-8">
            <div class="flex justify-start mb-2">
                <a href="{{ route('peserta.dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                    ← Kembali ke Dashboard
                </a>
            </div>
            <div id="toast" class="hidden fixed top-6 right-6 z-50"></div>
            <!-- Petunjuk Pengisian -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Petunjuk Pengisian:</h2>
                <div class="prose max-w-none">
                    @if(isset($sesiAssessment) && !empty($sesiAssessment->instruksi_khusus))
                        {!! $sesiAssessment->instruksi_khusus !!}
                    @elseif(!empty($assessment->petunjuk))
                        {!! $assessment->petunjuk !!}
                    @else
                        <p class="text-gray-500 italic">Petunjuk pengisian belum tersedia.</p>
                    @endif
                </div>
            </div>

            <!-- Konten Assessment -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              

                @switch($assessment->jenis)
                    @case('in_tray')
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Daftar Memo</h2>
                        <!--<p class="text-sm text-gray-600 mb-4">Urutkan kartu dari prioritas tertinggi (atas) ke terendah (bawah). Anda juga bisa tekan tombol “Lihat Detail” pada tiap kartu.</p> -->
                        <div id="inTrayBoard" class="grid grid-cols-1 gap-3">
                            @foreach($memos as $memo)
                                <div class="memo-card border border-gray-200 rounded-lg p-4 bg-gray-50 cursor-move" data-id="{{ $memo->id }}" data-content='@json($memo->konten_memo)'>
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="pt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-medium bg-blue-100 text-blue-800 border border-blue-200">Prioritas Memo M-{{$memo->id}}</span>
                                                <input type="number" min="1" class="memo-prioritas hidden w-20 border-black-300 m-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-black-500 bg-white-100" value="1" readonly>
                                                <span class="memo-prioritas-badge inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200 m-2">1</span>
                                            </div>
                                        </div>
                                        <button type="button" class="memo-detail inline-flex items-center px-2 py-1 text-xs border border-gray-300 rounded-md bg-white hover:bg-gray-50">Lihat Detail</button>
                                    </div>
                                    <div class="text-sm text-gray-700 mb-3" style="display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient: vertical; overflow:hidden;">
                                        {!! $memo->konten_memo !!}
                                    </div>
                                    

                                    <input type="hidden" class="memo-disposisi" value="{{ optional($inTrayAnswers->get($memo->id))->disposisi }}">
                                    @php $__disp = optional($inTrayAnswers->get($memo->id))->disposisi; @endphp
                                    <div class="memo-disposisi-text text-xxs text-gray-600 mt-1">
                                        <span class="font-medium">Disposisi:</span>
                                        <span class="memo-disposisi-text-value">{{ $__disp ? $__disp : 'belum dimasukkan' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form id="inTrayForm" class="mt-6">
                            @csrf
                            <div class="flex gap-3">
                                <button type="button" id="saveInTrayDraft" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan Sementara</button>
                                <button type="button" id="saveInTrayFinal" class="px-4 py-2 bg-green-600 text-white rounded-md">Simpan Final</button>
                            </div>
                        </form>
                        @break
                    @case('roleplay')
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Catatan Saya</h2>
                     <!--   @if(isset($items) && $items->count())
                            <div class="space-y-4 mb-6">
                                @foreach($items as $q)
                                    <div class="border border-gray-200 rounded-md p-4 bg-gray-50">
                                        <h4 class="font-semibold text-gray-800 mb-1">{{ $q->judul }}</h4>
                                        <div class="text-gray-700">{!! $q->konten !!}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif -->
                        <textarea id="roleplayText" rows="10" class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Tuliskan jawaban Anda di sini..."></textarea>
                        <div class="mt-4 flex gap-3">
                            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md" onclick="submitSimple('roleplay','draft')">Simpan Sementara</button>
                            <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-md" onclick="submitSimple('roleplay','final')">Simpan Final</button>
                        </div>
                        @break

                    @case('fgd')
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Catatan Saya</h2>
                       <!-- @if(isset($items) && $items->count())
                            <div class="space-y-4 mb-6">
                                @foreach($items as $q)
                                    <div class="border border-gray-200 rounded-md p-4 bg-gray-50">
                                        <h4 class="font-semibold text-gray-800 mb-1">{{ $q->judul }}</h4>
                                        <div class="text-gray-700">{!! $q->konten !!}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif -->
                        <textarea id="fgdText" rows="10" class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Tuliskan jawaban Anda di sini..."></textarea>
                        <div class="mt-4 flex gap-3">
                            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md" onclick="submitSimple('fgd','draft')">Simpan Sementara</button>
                            <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-md" onclick="submitSimple('fgd','final')">Simpan Final</button>
                        </div>
                        @break

                    @default
                        <p class="text-gray-600">Jenis assessment tidak dikenali.</p>
                @endswitch
            </div>

            @if(false)
            @endif

            @if(isset($sesiAssessment) && (int) $sesiAssessment->durasi_default > 0)
                <!-- Timer Section -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
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
</div>

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
            <div class="bg-white w-11/12 md:w-2/3 lg:w-1/3 rounded-lg shadow p-5 border">
                <div id="globalPopupContent" class="text-center text-gray-800"></div>
            </div>`;
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

// Drag & drop sederhana tanpa library
function makeSortable(container) {
    let dragSrcEl = null;
    container.querySelectorAll('.memo-card').forEach(card => {
        card.setAttribute('draggable', 'true');
        card.addEventListener('dragstart', (e) => {
            dragSrcEl = card;
            e.dataTransfer.effectAllowed = 'move';
            card.classList.add('opacity-50');
        });
        card.addEventListener('dragend', () => {
            card.classList.remove('opacity-50');
            updateMemoOrders(container);
        });
        card.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });
        card.addEventListener('drop', (e) => {
            e.stopPropagation();
            if (dragSrcEl !== card) {
                const list = Array.from(container.children);
                const srcIndex = list.indexOf(dragSrcEl);
                const destIndex = list.indexOf(card);
                if (srcIndex < destIndex) {
                    container.insertBefore(dragSrcEl, card.nextSibling);
                } else {
                    container.insertBefore(dragSrcEl, card);
                }
            }
        });
    });
    updateMemoOrders(container);
}

function updateMemoOrders(container) {
    const cards = container.querySelectorAll('.memo-card');
    cards.forEach((card, idx) => {
        const orderEl = card.querySelector('.memo-order');
        if (orderEl) orderEl.textContent = idx + 1;
        const inputPrioritas = card.querySelector('.memo-prioritas');
        if (inputPrioritas) inputPrioritas.value = idx + 1;
        const badge = card.querySelector('.memo-prioritas-badge');
        if (badge) badge.textContent = idx + 1;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const board = document.getElementById('inTrayBoard');
    if (board) {
        makeSortable(board);
        // tombol detail
        board.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('memo-detail')) {
                const card = e.target.closest('.memo-card');
                const html = JSON.parse(card.getAttribute('data-content'));
                openMemoModal(html, card);
            }
        });
    }

    // Inisialisasi CKEditor 5 Classic untuk roleplay & fgd (toolbar basic)
    window.editorInstances = {};
    const basicToolbar = ['bold','italic','link','bulletedList','numberedList','undo','redo'];
    if (document.getElementById('roleplayText')) {
        ClassicEditor.create(document.getElementById('roleplayText'), { toolbar: basicToolbar })
            .then(ed => { window.editorInstances['roleplayText'] = ed; })
            .catch(err => console.error(err));
    }
    if (document.getElementById('fgdText')) {
        ClassicEditor.create(document.getElementById('fgdText'), { toolbar: basicToolbar })
            .then(ed => { window.editorInstances['fgdText'] = ed; })
            .catch(err => console.error(err));
    }

    function collectPayload(status) {
        const payload = [];
        const cards = document.querySelectorAll('#inTrayBoard .memo-card');
        cards.forEach((card, idx) => {
            payload.push({
                latihan_in_tray_id: card.dataset.id,
                urutan_prioritas: idx + 1,
                disposisi: card.querySelector('.memo-disposisi')?.value || ''
            });
        });
        return {
            _token: '{{ csrf_token() }}',
            status: status,
            jawaban: payload
        };
    }

    function submitInTray(status) {
        const data = collectPayload(status);
        fetch(`{{ route('penilaian.in-tray.save', $assessment->id) }}` , {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify(data)
        }).then(async (res) => {
            if (!res.ok) throw new Error(await res.text());
            return res.json();
        }).then(() => {
            if (status === 'final') {
                showPopup('Jawaban final berhasil disimpan. Mengalihkan ke dashboard...', 'success', true);
            } else {
                showToast('Draft tersimpan', 'success');
            }
        }).catch(err => {
            console.error(err);
            if (status === 'final') {
                showPopup('Gagal menyimpan jawaban final. Silakan coba lagi.', 'error', false);
            } else {
                showToast('Gagal menyimpan jawaban', 'error');
            }
        });
    }

    const draftBtn = document.getElementById('saveInTrayDraft');
    const finalBtn = document.getElementById('saveInTrayFinal');
    if (draftBtn) draftBtn.addEventListener('click', () => submitInTray('draft'));
    if (finalBtn) finalBtn.addEventListener('click', () => submitInTray('final'));

    // Timer init tanpa directive Blade di dalam JS
    var remainingTime = parseInt('{{ isset($sesiAssessment) && (int) $sesiAssessment->durasi_default > 0 ? (int) $sesiAssessment->durasi_default * 60 : 0 }}', 10) || 0;
    if (remainingTime > 0) {
        function updateTimer() {
            if (remainingTime <= 0) {
                const t = document.getElementById('timer');
                if (t) t.textContent = '00:00';
                alert('Waktu pengerjaan assessment telah habis!');
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
});

// Modal sederhana untuk detail memo
let currentMemoCard = null;
function openMemoModal(html, card) {
    currentMemoCard = card;
    let modal = document.getElementById('memoModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'memoModal';
        modal.className = 'fixed inset-0 z-50 hidden';
        modal.innerHTML = `
            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
            <div class="relative w-full h-full bg-white flex flex-col">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-base md:text-lg font-semibold">Detail Memo</h3>
                    <div class="flex items-center gap-2">
                        <button id="memoModalClose" class="px-3 py-1.5 text-sm border rounded hover:bg-gray-50">Tutup</button>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto p-4 md:p-6">
                    <div id="memoModalContent" class="prose max-w-none mb-6"></div>
                    <hr class="my-8 border-gray-200">
                    <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="p-4 md:p-5">
                            <label class="block text-sm font-medium text-gray-800 mb-2">Disposisi</label>
                            <textarea id="memoModalDisposisi" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="contoh: delegasi ke sekretaris, arsip, tindak lanjut, dll"></textarea>
                        </div>
                    </div>
                </div>
            </div>`;
        document.body.appendChild(modal);

        // Close handler
        document.getElementById('memoModalClose').addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Input sync handler
        document.addEventListener('input', (evt) => {
            if (evt.target && evt.target.id === 'memoModalDisposisi' && currentMemoCard) {
                const hidden = currentMemoCard.querySelector('.memo-disposisi');
                if (hidden) hidden.value = evt.target.value;
                const textValue = currentMemoCard.querySelector('.memo-disposisi-text-value');
                if (textValue) {
                    const v = (evt.target.value || '').trim();
                    textValue.textContent = v.length ? v : 'belum dimasukkan';
                }
            }
        });
    }

    // Set content and initial disposisi
    const contentEl = document.getElementById('memoModalContent');
    const disposisiEl = document.getElementById('memoModalDisposisi');
    if (contentEl) contentEl.innerHTML = html;
    const hidden = card.querySelector('.memo-disposisi');
    if (disposisiEl) disposisiEl.value = hidden?.value || '';

    modal.classList.remove('hidden');
}

// Tailwind toast helper
function showToast(message, type = 'success') {
    const el = document.getElementById('toast');
    if (!el) return;
    const color = type === 'success' ? 'green' : 'red';
    el.className = `fixed top-6 right-6 z-50`;
    el.innerHTML = `
        <div class="rounded-md bg-${color}-50 border border-${color}-200 px-4 py-3 text-${color}-800 shadow">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-${color}-500"></span>
                <span class="text-sm font-medium">${message}</span>
            </div>
        </div>`;
    el.classList.remove('hidden');
    setTimeout(() => { el.classList.add('hidden'); }, 2500);
}

// Submit sederhana untuk roleplay/fgd (ambil data dari ClassicEditor)
function submitSimple(type, status) {
    const isRoleplay = type === 'roleplay';
    const instanceId = isRoleplay ? 'roleplayText' : 'fgdText';
    const editor = (window.editorInstances || {})[instanceId];
    const text = editor ? (editor.getData() || '') : (document.getElementById(instanceId)?.value || '');
    if (!text.trim()) {
        showToast('Teks belum diisi', 'error');
        return;
    }
    const url = isRoleplay ? `{{ route('penilaian.roleplay.save', $assessment->id) }}`
                            : `{{ route('penilaian.fgd.save', $assessment->id) }}`;
    const body = { catatan: text, status };
    fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify(body)
    }).then(async (res) => {
        if (!res.ok) throw new Error(await res.text());
        return res.json();
    }).then(() => {
        if (status === 'final') {
            showPopup('Simpan final berhasil. Mengalihkan ke dashboard...', 'success', true);
        } else {
            showToast(isRoleplay ? 'Catatan roleplay tersimpan' : 'Catatan FGD tersimpan', 'success');
        }
    }).catch(err => {
        console.error(err);
        if (status === 'final') {
            showPopup('Gagal menyimpan final. Silakan coba lagi.', 'error', false);
        } else {
            showToast('Gagal menyimpan', 'error');
        }
    });
}
</script>
@endsection
