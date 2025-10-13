@extends('peserta.layouts.app')

@section('title', 'Pengerjaan Assessment')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" data-sesi-penilaian-id="{{ $effectiveSesiId }}">
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
            <!-- Petunjuk Pengerjaan -->
          <!--   @if($assessment->jenis == 'roleplay' || $assessment->jenis == 'fgd')
             @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Petunjuk Pengerjaan:</h2>
                <div class="prose max-w-none">
                    @if(!empty($assessment->petunjuk) && $assessment->jenis != 'in_tray')
                        {!! $assessment->petunjuk !!}
                    @elseif($assessment->jenis =='in_tray')
                        {{ strip_tags($sesiAssessment->instruksi_khusus) ?? 'Tidak ada Petunjuk Pengerjaan Khusus' }}
                    @else
                        <p class="text-gray-500 italic">Petunjuk Pengerjaan belum tersedia.</p>
                    @endif
                </div>
            </div>
            @endif
-->

            <!-- Konten Assessment -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                @switch($assessment->jenis)
                    @case('in_tray')
                      
                        
                       <!-- {{-- Debug information untuk troubleshooting --}}
                        @if(config('app.debug'))
                            <div class="mb-4 p-3 bg-yellow-100 border border-yellow-300 rounded text-xs">
                                <strong>Debug Info (In-Tray):</strong><br>
                                SesiAssessment ID: {{ $sesiAssessment->id ?? 'null' }}<br>
                                SesiAssessment Sesi ID: {{ $sesiAssessment->sesi_penilaian_id ?? 'null' }}<br>
                                Instruksi Khusus: {{ $sesiAssessment->instruksi_khusus ?? 'null' }}<br>
                                Assessment Petunjuk: {{ $assessment->petunjuk ?? 'null' }}<br>
                                Requested Sesi: {{ request('sesi') ?? 'null' }}
                            </div>
                        @endif -->
                        
                        <div class="mb-4 rounded-md border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
                            <p class="font-semibold mb-2">Petunjuk:</p>
                            @if(($intrayModel ?? 'urutan') === 'urutan')
                                <ul class="list-disc pl-5 space-y-1">
                                @if(!empty($assessment->petunjuk) && $assessment->jenis != 'in_tray')
                                    <li>{!! $assessment->petunjuk !!}</li>
                                @elseif($assessment->jenis =='in_tray')
                                    <li>{{ strip_tags($sesiAssessment->instruksi_khusus) ?? 'Tidak ada Petunjuk Pengerjaan Khusus' }}</li>
                                @else
                                @endif
                                    <li>Seret dan jatuhkan (drag & drop) kartu untuk mengatur <span class="font-medium">urutan prioritas</span>. Kartu di atas berarti prioritas lebih tinggi.</li>
                                    <li>Saat jumlah kartu banyak, gulir area daftar. Saat sedang menarik kartu dan mendekati tepi atas/bawah, daftar akan <span class="font-medium">auto-scroll</span>.</li>
                                    <li>Klik tombol <span class="font-medium">Lihat Detail</span> pada kartu untuk membuka detail memo dan <span class="font-medium">mengisi Disposisi</span>.</li>
                                    <li>Setelah Disposisi disimpan, ringkasannya muncul di bawah kartu pada baris <span class="italic">Disposisi</span>.</li>
                                </ul>
                            @else
                                <ul class="list-disc pl-5 space-y-1">
                                    @if(!empty($assessment->petunjuk) && $assessment->jenis != 'in_tray')
                                        <li>{!! $assessment->petunjuk !!}</li>
                                    @elseif($assessment->jenis =='in_tray')
                                        <li>{{ strip_tags($sesiAssessment->instruksi_khusus) ?? 'Tidak ada Petunjuk Pengerjaan Khusus' }}</li>
                                    @else
                                    @endif
                                    <li>Klik tombol <span class="font-medium">Lihat Detail</span> pada kartu untuk membuka detail memo.</li>
                                    <li>Dalam detail memo, pilih <span class="font-medium">kategori prioritas</span> sesuai dengan tingkat urgensi dan kepentingan memo.</li>
                                    <li>Isi <span class="font-medium">Disposisi</span> untuk menjelaskan tindakan yang akan diambil terhadap memo.</li>
                                    <li>Kemudian Susun langkah-langkah strategis yang harus dilakukan dalam beberapa hari ke depan untuk merespon dan mengantisipasi isu-isu penting seperti yang ada dalam memo-memo penting.</li>
                                    <li>Setelah semua informasi disimpan, ringkasannya akan muncul di bawah kartu memo.</li>
                                </ul>
                            @endif
                        </div>
                        <!--<h2 class="text-xl font-semibold text-gray-900 mt-4">Daftar Memo</h2>-->
                        <div id="inTrayBoard" class="grid grid-cols-1 gap-3 {{ ($intrayModel ?? 'urutan') === 'urutan' ? 'sortable' : '' }}">
                            @if($memos->count() > 0)
                                @foreach($memos as $memo)
                                    <div class="memo-card border border-gray-200 rounded-lg p-4 bg-gray-50 {{ ($intrayModel ?? 'urutan') === 'urutan' ? 'cursor-move' : '' }}" 
                                         data-id="{{ $memo->id }}" 
                                         data-content='@json(['konten_memo' => $memo->konten_memo, 'pertanyaan' => $memo->pertanyaan])'>
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex items-center gap-3">
                                                <div class="pt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-medium bg-blue-100 text-blue-800 border border-blue-200">Memo M-{{$memo->id}}</span>
                                                    <input type="number" min="1" class="memo-prioritas hidden w-20 border-black-300 m-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-black-500 bg-white-100" value="1" readonly>
                                                    @if(($intrayModel ?? 'urutan') === 'urutan')
                                                    <span class="memo-prioritas-badge inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200 m-2">1</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <button type="button" title="Lihat detail memo dan isi Disposisi" class="memo-detail inline-flex items-center px-2 py-1 text-xs border border-gray-300 rounded-md bg-white hover:bg-gray-50">Lihat Detail</button>
                                        </div>
                                        <div class="text-sm text-gray-700 mb-3" style="display:-webkit-box; -webkit-line-clamp:4; line-clamp:4; -webkit-box-orient: vertical; overflow:hidden;">
                                            {!! $memo->konten_memo !!}
                                        </div>
                                        
                                        <!-- Hidden inputs for data storage -->
                                        <input type="hidden" class="memo-disposisi" value="{{ optional($inTrayAnswers->get($memo->id))->disposisi }}">
                                        <input type="hidden" class="memo-priority-select" value="{{ optional($inTrayAnswers->get($memo->id))->prioritasMemo->kategori_prioritas ?? '' }}">
                                        <input type="hidden" class="memo-question-answer" value="{{ optional($inTrayAnswers->get($memo->id))->jawaban_pertanyaan ?? '' }}">
                                        
                                        <!-- Display current values -->
                                        @php 
                                            $__disp = optional($inTrayAnswers->get($memo->id))->disposisi;
                                            $__prioritas = optional($inTrayAnswers->get($memo->id))->prioritasMemo->kategori_prioritas ?? '';
                                            $__jawaban = optional($inTrayAnswers->get($memo->id))->jawaban_pertanyaan ?? '';
                                        @endphp
                                        
                                        <div class="memo-disposisi-text text-xxs text-gray-600 mt-1">
                                            <span class="font-medium">Disposisi:</span>
                                            <span class="memo-disposisi-text-value">{{ $__disp ? $__disp : 'belum dimasukkan' }}</span>
                                        </div>
                                        
                                        @if($intrayModel === 'prioritas')
                                        <div class="memo-priority-text text-xxs text-gray-600 mt-1">
                                            <span class="font-medium">Prioritas:</span>
                                            <span class="memo-priority-text-value">
                                                @if($__prioritas)
                                                    @switch($__prioritas)
                                                        @case('mendesak_penting')
                                                            Mendesak - Penting
                                                            @break
                                                        @case('mendesak_tidak_penting')
                                                            Mendesak - Tidak Penting
                                                            @break
                                                        @case('tidak_mendesak_penting')
                                                            Tidak Mendesak - Penting
                                                            @break
                                                        @case('tidak_mendesak_tidak_penting')
                                                            Tidak Mendesak - Tidak Penting
                                                            @break
                                                        @default
                                                            Belum dipilih
                                                    @endswitch
                                                @else
                                                    belum dipilih
                                                @endif
                                            </span>
                                        </div>
                                        @endif
                                        
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                    <div class="text-gray-500 text-lg font-medium mb-2">Belum ada memo</div>
                                    <div class="text-gray-400 text-sm">Admin belum menambahkan memo untuk sesi ini</div>
                                </div>
                            @endif
                        </div>
                        
                        @if($memos->count() > 0)
                        <!-- Question & Answer Section - Single question for entire in-tray assessment -->
                        <div class="mt-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="p-4 md:p-5">
                                <!--<h3 class="text-lg font-semibold text-gray-900 mb-4">Pertanyaan dan Jawaban</h3>-->
                                
                                @php
                                    // Get the first memo with a question, or use a default question
                                    $firstMemoWithQuestion = $memos->where('pertanyaan', '!=', null)->first();
                                    $defaultQuestion = 'Susun langkah-langkah strategis yang harus dilakukan dalam beberapa hari ke depan untuk merespon dan mengantisipasi isu-isu penting seperti yang ada dalam memo-memo penting.';
                                    $assessmentQuestion = $firstMemoWithQuestion ? $firstMemoWithQuestion->pertanyaan : $defaultQuestion;
                                    
                                    // Get existing answer from any memo (they should all be the same)
                                    $existingAnswer = '';
                                    foreach ($memos as $memo) {
                                        $answer = optional($inTrayAnswers->get($memo->id))->jawaban_pertanyaan ?? '';
                                        if (!empty($answer)) {
                                            $existingAnswer = $answer;
                                            break;
                                        }
                                    }
                                @endphp
                                
                                <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-800 mb-2">Pertanyaan untuk Assessment In-Tray</label>
                                        <div class="text-sm text-gray-600 bg-white p-3 rounded-md border">
                                            {!! $assessmentQuestion !!}
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-800 mb-2">Jawaban Anda</label>
                                        <div id="intrayQuestionAnswerEditor" class="border border-gray-300 rounded-md"></div>
                                        <textarea id="intrayQuestionAnswer" name="intray_question_answer" style="display: none;">{{ $existingAnswer }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($memos->count() > 0)
                        <form id="inTrayForm" class="mt-6">
                            @csrf
                            <div class="flex gap-3">
                                <button type="button" id="saveInTrayDraft" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan Sementara</button>
                                <button type="button" id="saveInTrayFinal" class="px-4 py-2 bg-green-600 text-white rounded-md">Simpan Final</button>
                                @if(($intrayModel ?? 'urutan') === 'prioritas')
                                    <a href="{{ route('peserta.intray-matrix.show', ['sesi' => $effectiveSesiId]) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Lihat Matriks
                                    </a>
                                @endif
                            </div>
                        </form>
                        @endif
                        @break
                    @case('roleplay')
                        <!-- Skenario dan Instruksi Role-Play -->
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Skenario dan Instruksi Role-Play</h2>
                            @if($assessment->file_pdf)
                                <!-- PDF untuk Role-Play -->
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
                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const container = document.getElementById('roleplayPdfViewer');
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
                                        container.innerHTML = '<div class="text-center text-red-600 p-8"><p>Gagal memuat PDF. Silakan refresh halaman.</p></div>';
                                    });
                                });
                                </script>
                            @elseif(isset($sesiAssessment) && !empty(trim($sesiAssessment->instruksi_khusus)))
                                <!-- Fallback: Instruksi Khusus jika tidak ada PDF -->
                                <div class="prose max-w-none bg-gray-50 p-4 rounded-lg border">
                                    {!! $sesiAssessment->instruksi_khusus !!}
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-32 text-gray-500">
                                    <p class="text-lg font-medium">Skenario Role-Play belum tersedia</p>
                                    <p class="text-sm">Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                                </div>
                            @endif
                        </div>
                        
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
                        <textarea id="roleplayText" rows="10" class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Tuliskan jawaban Anda di sini...">{!! $existingRoleplay ?? '' !!}</textarea>
                        <div class="mt-4 flex gap-3">
                            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md" onclick="submitSimple('roleplay','draft')">Simpan Sementara</button>
                            <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-md" onclick="submitSimple('roleplay','final')">Simpan Final</button>
                        </div>
                        @break

                    @case('fgd')
                        <!-- Topik dan Panduan LGD/FGD -->
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Topik dan Panduan LGD/FGD</h2>
                            @if($assessment->file_pdf)
                                <!-- PDF untuk LGD/FGD -->
                                @php
                                    $pdfUrl = route('assessment.pdf.view', ['penilaianId' => $assessment->id, 'filename' => $assessment->file_pdf]);
                                @endphp
                                <div class="w-full border rounded-md overflow-hidden">
                                    <iframe 
                                        style="width: 100%; height: 500px; border: 1px solid #eeeeee;" 
                                        src="{{ $pdfUrl }}#zoom=80" 
                                        width="100%" 
                                        height="500" 
                                        frameborder="0" 
                                        allowfullscreen="allowfullscreen">
                                    </iframe>
                                </div>
                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const container = document.getElementById('fgdPdfViewer');
                                    const originalUrl = "{{ $pdfUrl }}";
                                    
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
                                        container.innerHTML = '<div class="text-center text-red-600 p-8"><p>Gagal memuat PDF. Silakan refresh halaman.</p></div>';
                                    });
                                });
                                </script>
                            @elseif(isset($sesiAssessment) && !empty(trim($sesiAssessment->instruksi_khusus)))
                                <!-- Fallback: Instruksi Khusus jika tidak ada PDF -->
                                <div class="prose max-w-none bg-gray-50 p-4 rounded-lg border">
                                    {!! $sesiAssessment->instruksi_khusus !!}
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-32 text-gray-500">
                                    <p class="text-lg font-medium">Topik LGD/FGD belum tersedia</p>
                                    <p class="text-sm">Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                                </div>
                            @endif
                        </div>
                        
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
                        <textarea id="fgdText" rows="10" class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Tuliskan jawaban Anda di sini...">{!! $existingFgd ?? '' !!}</textarea>
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

<style>
.ck-editor__editable[role="textbox"] { min-height: 12rem; }
.ck-content ul { list-style: disc !important; list-style-position: outside !important; margin-left: 1.5rem !important; padding-left: 0 !important; }
.ck-content ol { list-style: decimal !important; list-style-position: outside !important; margin-left: 1.5rem !important; padding-left: 0 !important; }
/* In-tray: memungkinkan scroll saat kartu banyak */
#inTrayBoard { max-height: 65vh; overflow-y: auto; padding-right: 4px; }
.memo-card { scroll-margin: 16px; }
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
    
    const cards = container.querySelectorAll('.memo-card');
    console.log('makeSortable: Found', cards.length, 'cards');
    
    cards.forEach((card, index) => {
        console.log('Setting up card', index, 'with ID:', card.dataset.id);
        
        // Clone the card to remove all existing event listeners
        const newCard = card.cloneNode(true);
        card.parentNode.replaceChild(newCard, card);
        
        newCard.setAttribute('draggable', 'true');
        
        newCard.addEventListener('dragstart', (e) => {
            console.log('dragstart on card', index, 'ID:', newCard.dataset.id);
            dragSrcEl = newCard;
            e.dataTransfer.effectAllowed = 'move';
            newCard.classList.add('opacity-50');
        });
        
        newCard.addEventListener('dragend', (e) => {
            console.log('dragend on card', index, 'ID:', newCard.dataset.id);
            newCard.classList.remove('opacity-50');
            updateMemoOrders(container);
        });
        
        newCard.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });
        
        newCard.addEventListener('drop', (e) => {
            console.log('drop on card', index, 'ID:', newCard.dataset.id);
            e.stopPropagation();
            if (dragSrcEl !== newCard) {
                const list = Array.from(container.children);
                const srcIndex = list.indexOf(dragSrcEl);
                const destIndex = list.indexOf(newCard);
                if (srcIndex < destIndex) {
                    container.insertBefore(dragSrcEl, newCard.nextSibling);
                } else {
                    container.insertBefore(dragSrcEl, newCard);
                }
            }
        });
    });

    // Auto-scroll saat drag mendekati tepi atas/bawah kontainer
    container.addEventListener('dragover', (e) => {
        e.preventDefault();
        const threshold = 60; // px dari tepi untuk mulai auto-scroll
        const rect = container.getBoundingClientRect();
        if (e.clientY < rect.top + threshold) {
            container.scrollTop -= 12;
        } else if (e.clientY > rect.bottom - threshold) {
            container.scrollTop += 12;
        }
    });

    // Izinkan drop ke area kosong kontainer (mis. di bawah kartu terakhir)
    container.addEventListener('drop', (e) => {
        e.preventDefault();
        if (!dragSrcEl) return;
        const targetCard = e.target.closest('.memo-card');
        if (!targetCard) {
            container.appendChild(dragSrcEl);
        }
        updateMemoOrders(container);
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
        // tombol detail
        board.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('memo-detail')) {
                const card = e.target.closest('.memo-card');
                const memoData = JSON.parse(card.getAttribute('data-content'));
                const html = memoData.konten_memo || '';
                openMemoModal(html, card);
            }
        });
    }

    // Initialize model based on assessment type
    const assessmentModel = '{{ $intrayModel ?? "urutan" }}';
    console.log('=== ASSESSMENT MODE DEBUG ===');
    console.log('Assessment Model:', assessmentModel);
    console.log('Assessment Type:', '{{ $assessment->jenis }}');
    console.log('Assessment ID:', '{{ $assessment->id }}');
    console.log('Assessment Name:', '{{ $assessment->nama }}');
    console.log('=============================');
    initializeInTrayModel(assessmentModel);

    // URL simpan In-Tray
    const IN_TRAY_SAVE_URL = "{{ route('penilaian.in-tray.save', $assessment->id) }}";
    const CSRF_TOKEN = document.querySelector('#inTrayForm input[name="_token"]')?.value || '{{ csrf_token() }}';
    const SESI_PENILAIAN_ID = parseInt(document.querySelector('[data-sesi-penilaian-id]').getAttribute('data-sesi-penilaian-id'));

    function collectInTrayAnswers() {
        const container = document.getElementById('inTrayBoard');
        if (!container) return [];
        const cards = Array.from(container.querySelectorAll('.memo-card'));
        
        // Get the single question answer from the editor
        const intrayQuestionAnswer = window.intrayQuestionEditor ? window.intrayQuestionEditor.getData() : '';
        
        return cards.map((card, idx) => {
            const answer = {
                latihan_in_tray_id: parseInt(card.getAttribute('data-id'), 10),
                urutan_prioritas: idx + 1,
                disposisi: (card.querySelector('.memo-disposisi')?.value || '').trim(),
                jawaban_pertanyaan: intrayQuestionAnswer.trim() // Use the single answer for all memos
            };

            // Add priority selection if using priority model
            const prioritySelect = card.querySelector('.memo-priority-select');
            if (prioritySelect) {
                answer.kategori_prioritas = prioritySelect.value;
            }

            return answer;
        });
    }

    async function submitInTray(status) {
        const saveDraftBtn = document.getElementById('saveInTrayDraft');
        const saveFinalBtn = document.getElementById('saveInTrayFinal');
        const disable = (v) => { if (saveDraftBtn) saveDraftBtn.disabled = v; if (saveFinalBtn) saveFinalBtn.disabled = v; };
        try {
            disable(true);
            const jawaban = collectInTrayAnswers();
            if (!jawaban.length) {
                showPopup('Tidak ada memo untuk disimpan.', 'error', false);
                disable(false);
                return;
            }
            const res = await fetch(IN_TRAY_SAVE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                credentials: 'same-origin',
                body: JSON.stringify({ 
                    jawaban, 
                    status, 
                    sesi_penilaian_id: SESI_PENILAIAN_ID
                })
            });
            if (!res.ok) {
                let msg = 'Gagal menyimpan in-tray.';
                try { const data = await res.json(); if (data && data.error) msg = data.error; if (data && data.message) msg = data.message; } catch (_) {}
                showPopup(msg, 'error', false);
                return;
            }
            const data = await res.json();
            const isFinal = status === 'final';
            showPopup(data.message || (isFinal ? 'Simpan final berhasil.' : 'Simpan sementara berhasil.'), 'success', isFinal);
        } catch (err) {
            showPopup('Terjadi kesalahan jaringan. Coba lagi.', 'error', false);
        } finally {
            disable(false);
        }
    }

    const btnDraft = document.getElementById('saveInTrayDraft');
    if (btnDraft) btnDraft.addEventListener('click', () => submitInTray('draft'));
    const btnFinal = document.getElementById('saveInTrayFinal');
    if (btnFinal) btnFinal.addEventListener('click', () => submitInTray('final'));

    // Inisialisasi CKEditor 5 Classic basic untuk studi kasus (hanya jika elemen tersedia)
    const jawabanEl = document.getElementById('jawaban');
    if (jawabanEl) {
        let jawabanInit = `{!! addslashes($existingJawaban ?? '') !!}`;
        ClassicEditor.create(jawabanEl, { 
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
        })
            .then(ed => { window.jawabanEditor = ed; if (jawabanInit) { ed.setData(jawabanInit); } })
            .catch(err => console.error(err));
    }

    // Inisialisasi CKEditor untuk Roleplay dan FGD agar konsisten
    const roleplayEl = document.getElementById('roleplayText');
    if (roleplayEl) {
        const initVal = `{!! addslashes($existingRoleplay ?? '') !!}`;
        ClassicEditor.create(roleplayEl, { 
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
        })
            .then(ed => { window.roleplayEditor = ed; if (initVal) ed.setData(initVal); })
            .catch(err => console.error(err));
    }
    const fgdEl = document.getElementById('fgdText');
    if (fgdEl) {
        const initVal = `{!! addslashes($existingFgd ?? '') !!}`;
        ClassicEditor.create(fgdEl, { 
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
        })
            .then(ed => { window.fgdEditor = ed; if (initVal) ed.setData(initVal); })
            .catch(err => console.error(err));
    }

    // Submit sederhana untuk Roleplay/FGD (draft/final)
    window.submitSimple = async function(kind, status) {
        try {
            const isRoleplay = kind === 'roleplay';
            const editor = isRoleplay ? window.roleplayEditor : window.fgdEditor;
            const textareaId = isRoleplay ? 'roleplayText' : 'fgdText';
            const val = editor ? editor.getData() : (document.getElementById(textareaId)?.value || '');
            const url = isRoleplay
                ? "{{ route('penilaian.roleplay.save', $assessment->id) }}"
                : "{{ route('penilaian.fgd.save', $assessment->id) }}";
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ 
                    catatan: val, 
                    status, 
                    sesi_penilaian_id: SESI_PENILAIAN_ID 
                })
            });
            if (!res.ok) {
                let msg = 'Gagal menyimpan catatan.';
                try { const data = await res.json(); if (data && data.error) msg = data.error; if (data && data.message) msg = data.message; } catch(_) {}
                showPopup(msg, 'error', false);
                return;
            }
            const data = await res.json();
            const isFinal = status === 'final';
            showPopup((data && data.message) || (isFinal ? 'Simpan final berhasil.' : 'Simpan sementara berhasil.'), 'success', isFinal);
        } catch (e) {
            showPopup('Terjadi kesalahan jaringan. Coba lagi.', 'error', false);
        }
    }

    // Countdown timer dll tetap sama (ada di bawah)
});

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
        formData.append('jawaban', window.jawabanEditor ? window.jawabanEditor.getData() : (document.getElementById('jawaban')?.value || ''));
        formData.append('assessment_action', 'draft');
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

setTimeout(() => {
    if (window.jawabanEditor) {
        window.jawabanEditor.model.document.on('change:data', scheduleAutoSave);
    }
}, 500);

// Konfirmasi sebelum meninggalkan halaman jika ada perubahan
window.addEventListener('beforeunload', function(e) {
    if (isSubmitting) {
        return;
    }
    const currentVal = window.jawabanEditor ? window.jawabanEditor.getData() : (document.getElementById('jawaban')?.value || '');
    if (hasChanges && currentVal.trim() !== `{{ old('jawaban', $existingJawaban ?? '') }}`.trim()) {
        e.preventDefault();
        e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
    }
});

if (formEl) {
    formEl.addEventListener('submit', async function(e) {
        if (window.jawabanEditor) {
            document.getElementById('jawaban').value = window.jawabanEditor.getData();
        }
    });
}

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
                    
                    <!-- Priority Selection Section -->
                    <div id="memoModalPrioritySection" class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm" style="display: none;">
                        <div class="p-4 md:p-5">
                            <label class="block text-sm font-medium text-gray-800 mb-2">Pilih Prioritas</label>
                            <select id="memoModalPriority" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Prioritas</option>
                                <option value="mendesak_penting">Mendesak - Penting</option>
                                <option value="mendesak_tidak_penting">Mendesak - Tidak Penting</option>
                                <option value="tidak_mendesak_penting">Tidak Mendesak - Penting</option>
                                <option value="tidak_mendesak_tidak_penting">Tidak Mendesak - Tidak Penting</option>
                            </select>
                        </div>
                    </div>


                    <!-- Disposisi Section -->
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
            if (!currentMemoCard) return;
            
            if (evt.target && evt.target.id === 'memoModalDisposisi') {
                const hidden = currentMemoCard.querySelector('.memo-disposisi');
                if (hidden) hidden.value = evt.target.value;
                const textValue = currentMemoCard.querySelector('.memo-disposisi-text-value');
                if (textValue) {
                    const v = (evt.target.value || '').trim();
                    textValue.textContent = v.length ? v : 'belum dimasukkan';
                }
            }
            
            if (evt.target && evt.target.id === 'memoModalPriority') {
                const prioritySelect = currentMemoCard.querySelector('.memo-priority-select');
                if (prioritySelect) prioritySelect.value = evt.target.value;
                
                // Update priority display text
                const priorityTextValue = currentMemoCard.querySelector('.memo-priority-text-value');
                if (priorityTextValue) {
                    const priorityValue = evt.target.value;
                    let priorityLabel = 'belum dipilih';
                    
                    switch(priorityValue) {
                        case 'mendesak_penting':
                            priorityLabel = 'Mendesak - Penting';
                            break;
                        case 'mendesak_tidak_penting':
                            priorityLabel = 'Mendesak - Tidak Penting';
                            break;
                        case 'tidak_mendesak_penting':
                            priorityLabel = 'Tidak Mendesak - Penting';
                            break;
                        case 'tidak_mendesak_tidak_penting':
                            priorityLabel = 'Tidak Mendesak - Tidak Penting';
                            break;
                    }
                    
                    priorityTextValue.textContent = priorityLabel;
                }
            }
            
        });
    }

    // Set content and initial values
    const contentEl = document.getElementById('memoModalContent');
    const disposisiEl = document.getElementById('memoModalDisposisi');
    const priorityEl = document.getElementById('memoModalPriority');
    const prioritySection = document.getElementById('memoModalPrioritySection');
    
    if (contentEl) contentEl.innerHTML = html;
    
    // Set disposisi value from hidden input
    const hiddenDisposisi = card.querySelector('.memo-disposisi');
    if (disposisiEl) disposisiEl.value = hiddenDisposisi?.value || '';
    
    // Set priority value from hidden input
    const hiddenPriority = card.querySelector('.memo-priority-select');
    if (priorityEl && hiddenPriority) {
        priorityEl.value = hiddenPriority.value || '';
    }
    
    
    // Show/hide sections based on model
    const intrayModel = '{{ $intrayModel ?? "urutan" }}';
    
    if (prioritySection) {
        prioritySection.style.display = intrayModel === 'prioritas' ? 'block' : 'none';
    }
    

    modal.classList.remove('hidden');
}

// Initialize in-tray model based on assessment configuration
function initializeInTrayModel(model = 'urutan') {
    console.log('initializeInTrayModel called with model:', model);
    if (model === 'prioritas') {
        console.log('✅ Enabling PRIORITAS model (4 kategori prioritas)');
        // Enable priority model
        enablePriorityModel();
    } else {
        console.log('✅ Enabling URUTAN model (drag & drop)');
        // Enable order model (default)
        enableOrderModel();
    }
}

// Enable priority model
function enablePriorityModel() {
    console.log('🔧 Setting up PRIORITAS model...');
    const board = document.getElementById('inTrayBoard');
    if (!board) {
        console.log('❌ Board not found');
        return;
    }
    
    const cards = board.querySelectorAll('.memo-card');
    console.log('📋 Found', cards.length, 'memo cards for prioritas model');
    
    cards.forEach((card, index) => {
        // Hide order badge
        const orderBadge = card.querySelector('.memo-prioritas-badge');
        if (orderBadge) {
            orderBadge.style.display = 'none';
        }
        
        // Make card non-draggable
        card.setAttribute('draggable', 'false');
        card.classList.remove('cursor-move');
    });
    
    // Disable sortable functionality
    board.classList.remove('sortable');
    console.log('✅ PRIORITAS model setup complete - No drag & drop, priority selection in modal');
}

// Enable order model
function enableOrderModel() {
    console.log('🔧 Setting up URUTAN model...');
    const board = document.getElementById('inTrayBoard');
    if (!board) {
        console.log('❌ Board not found');
        return;
    }
    
    const cards = board.querySelectorAll('.memo-card');
    console.log('📋 Found', cards.length, 'memo cards for urutan model');
    
    cards.forEach((card, index) => {
        console.log('🎯 Setting up card', index, 'for order model, ID:', card.dataset.id);
        
        // Show order badge
        const orderBadge = card.querySelector('.memo-prioritas-badge');
        if (orderBadge) {
            orderBadge.style.display = 'inline-flex';
            console.log('🏷️ Order badge shown for card', index);
        }
        
        // Make card draggable
        card.setAttribute('draggable', 'true');
        card.classList.add('cursor-move');
        console.log('🖱️ Card', index, 'made draggable');
    });
    
    // Enable sortable functionality
    board.classList.add('sortable');
    console.log('🔄 Calling makeSortable...');
    makeSortable(board);
    console.log('✅ URUTAN model setup complete - Drag & drop enabled, priority badges shown');
}

// Toggle between models (for testing purposes)
function toggleInTrayModel() {
    const board = document.getElementById('inTrayBoard');
    if (!board) return;
    
    const prioritySections = document.querySelectorAll('.memo-priority-section');
    const hasPriorityModel = prioritySections.length > 0 && prioritySections[0].style.display !== 'none';
    
    if (hasPriorityModel) {
        enableOrderModel();
    } else {
        enablePriorityModel();
    }
}

// Initialize CKEditor for single in-tray question answer
document.addEventListener('DOMContentLoaded', function() {
    const intrayQuestionEditorEl = document.getElementById('intrayQuestionAnswerEditor');
    if (intrayQuestionEditorEl) {
        ClassicEditor
            .create(intrayQuestionEditorEl, {
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
                },
                height: 200
            })
            .then(editor => {
                window.intrayQuestionEditor = editor;
                
                // Set initial value
                const textarea = document.getElementById('intrayQuestionAnswer');
                if (textarea) {
                    editor.setData(textarea.value || '');
                }
                
                // Listen for changes
                editor.model.document.on('change:data', () => {
                    if (textarea) {
                        textarea.value = editor.getData();
                    }
                });
            })
            .catch(error => {
                console.error('Error initializing CKEditor for in-tray question:', error);
            });
    }
});
</script>
@endsection
