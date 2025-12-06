<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Center - Dashboard Peserta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">Assessment Center</h1>
                    <div class="w-px h-8 bg-gray-300"></div>
                    <span class="text-gray-600">Dashboard Peserta</span>
                    <!--<a href="{{ route('peserta.petunjuk') }}" class="ml-4 inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Petunjuk Penggunaan
                    </a>-->
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-gray-900 font-medium">{{ $peserta->nama_lengkap }}</p>
                        <p class="text-gray-500 text-sm">{{ $peserta->instansi ?? 'Tidak ada instansi' }}</p>
                    </div>
                    <form method="POST" action="{{ route('peserta.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <h4 class="font-medium">Error:</h4>
                <ul class="list-disc list-inside mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tombol Biodata di Paling Atas -->
        <div class="mb-8 text-center">
            <a href="{{ route('peserta.biodata') }}" 
               class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Lihat Biodata</span>
            </a>
        </div>

        <!-- Daftar Sesi Peserta -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sesi Assessment Anda</h2>
            
            @if($sesiList->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($sesiList as $sesi)
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <!-- Header Sesi -->
                            <div class="p-6 border-b border-gray-100">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $sesi->nama }}</h3>
                                        <!--<p class="text-sm text-gray-600">
                                            @if($sesi->durasi_menit)
                                                {{ $sesi->durasi_menit }} menit
                                            @else
                                                Durasi tidak ditetapkan
                                            @endif
                                        </p>-->
                                    </div>
                                    <div class="ml-4">
                                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                            @if($sesi->status === 'draft') bg-gray-100 text-gray-800 border border-gray-200
                                            @elseif($sesi->status === 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                            @elseif($sesi->status === 'active') bg-green-100 text-green-800 border border-green-200
                                            @elseif($sesi->status === 'paused') bg-orange-100 text-orange-800 border border-orange-200
                                            @else bg-blue-100 text-blue-800 border border-blue-200
                                            @endif">
                                            @if($sesi->status === 'draft') Draft
                                            @elseif($sesi->status === 'pending') Menunggu
                                            @elseif($sesi->status === 'active') Aktif
                                            @elseif($sesi->status === 'paused') Dijeda
                                            @else Selesai
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                
                                                                 <!-- Jenis Assessment -->
                                 <div class="flex flex-wrap gap-2">
                                     @foreach($sesi->assessments as $assessment)
                                         @php
                                             $statusKemajuan = $progressMap[$assessment->penilaian->id . '_' . $sesi->id] ?? null;
                                             $isSedangBerlangsung = $statusKemajuan === 'sedang_berlangsung';
                                             $isSelesai = $statusKemajuan === 'selesai';
                                             $belumMulai = $statusKemajuan === null || $statusKemajuan === 'belum_mulai';
                                             
                                             // Warna dan tooltip berdasarkan status
                                             if ($isSelesai) {
                                                 $warnaKelas = 'border-green-300 text-green-800 bg-green-50 hover:bg-green-100';
                                                 $tooltipText = 'Assessment Selesai - Klik untuk melihat hasil';
                                             } elseif ($isSedangBerlangsung) {
                                                 $warnaKelas = 'border-yellow-300 text-yellow-800 bg-yellow-50 hover:bg-yellow-100';
                                                 $tooltipText = 'Assessment Sedang Berlangsung - Klik untuk melanjutkan';
                                             } else {
                                                 // Belum mulai atau belum ada data
                                                 $warnaKelas = 'border-blue-300 text-blue-800 bg-blue-50 hover:bg-blue-100';
                                                 $tooltipText = 'Assessment Belum Dimulai - Klik untuk memulai';
                                             }
                                         @endphp
                                         @if($sesi->status === 'active')
                                             @if($assessment->penilaian->jenis === 'studi_kasus')
                                                 <a href="{{ route('peserta.assessment.studi-kasus', ['id' => $assessment->penilaian->id, 'sesi' => $sesi->id]) }}"
                                                    title="{{ $tooltipText }}"
                                                    class="inline-flex items-center space-x-1 px-3 py-1 text-xs font-medium rounded-full border {{ $warnaKelas }} transition-colors duration-200 cursor-pointer">
                                                     @if($isSelesai)
                                                         <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                             <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                         </svg>
                                                     @elseif($isSedangBerlangsung)
                                                         <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                         </svg>
                                                     @else
                                                         <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                         </svg>
                                                     @endif
                                                     <span>{{ $assessment->penilaian->jenis_text }}</span>
                                                 </a>
                                             @else
                                                 <a href="{{ route('peserta.assessment.kerja', ['id' => $assessment->penilaian->id, 'sesi' => $sesi->id]) }}"
                                                    title="{{ $tooltipText }}"
                                                    class="inline-flex items-center space-x-1 px-3 py-1 text-xs font-medium rounded-full border {{ $warnaKelas }} transition-colors duration-200 cursor-pointer">
                                                     @if($isSelesai)
                                                         <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                             <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                         </svg>
                                                     @elseif($isSedangBerlangsung)
                                                         <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                         </svg>
                                                     @else
                                                         <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                         </svg>
                                                     @endif
                                                     <span>{{ $assessment->penilaian->jenis_text }}</span>
                                                 </a>
                                             @endif
                                         @else
                                             <span class="px-3 py-1 text-xs font-medium rounded-full border border-gray-300 text-gray-500 bg-gray-100 cursor-not-allowed">
                                                 {{ $assessment->penilaian->jenis_text }}
                                             </span>
                                         @endif
                                     @endforeach
                                 </div>
                                 
                                 <!-- Keterangan Status Sesi -->
                                 @if($sesi->status !== 'active')
                                     <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                         <div class="flex items-center">
                                             <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                             </svg>
                                             <p class="text-sm text-yellow-800">
                                                 Status sesi <span class="font-medium">{{ ucfirst($sesi->status) }}</span> sehingga pengerjaan assessment tidak bisa dilakukan
                                             </p>
                                         </div>
                                     </div>
                                 @endif
                            </div>
                            
                            <!-- Action Buttons -->
                           <!--  <div class="p-6">
                                <div class="space-y-3">
                                    <a href="{{ route('peserta.sesi.detail', $sesi->id) }}" 
                                       class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>-->
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Tidak Ada Sesi -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Sesi</h3>
                    <p class="text-gray-500 mb-4">Anda belum terdaftar di sesi assessment manapun.</p>
                    <p class="text-sm text-gray-400">Silakan hubungi admin untuk mendaftarkan Anda ke sesi assessment.</p>
                </div>
            @endif
        </div>

       
    </div>
</body>
</html>
