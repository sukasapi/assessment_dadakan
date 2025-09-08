<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Center - Detail Sesi</title>
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
                    <span class="text-gray-600">Detail Sesi</span>
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
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('peserta.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $sesi->nama }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Sesi Info Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $sesi->nama }}</h2>
                        <p class="text-lg text-gray-600 mb-4">{{ $sesi->catatan ?? 'Tidak ada deskripsi' }}</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                         <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                 <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full
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
                                 
                                 @if($sesi->status !== 'active')
                                     <p class="text-xs text-gray-500 mt-1">
                                         ⚠️ Assessment tidak dapat dikerjakan
                                     </p>
                                 @else
                                     <p class="text-xs text-green-600 mt-1">
                                         ✅ Assessment dapat dikerjakan
                                     </p>
                                 @endif
                             </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Durasi</label>
                                <p class="text-gray-900">
                                    @if($sesi->durasi_menit)
                                        {{ $sesi->durasi_menit }} menit
                                    @else
                                        Tidak ditetapkan
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dibuat</label>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($sesi->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assessment List -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Assessment</h3>
                <p class="text-sm text-gray-600 mb-6">Berikut adalah assessment yang akan Anda kerjakan dalam sesi ini:</p>
                
                <!-- Keterangan Status Sesi -->
                @if($sesi->status !== 'active')
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-yellow-800">
                                                    Status sesi <span class="font-bold">{{ ucfirst($sesi->status) }}</span>
                                                </p>
                                                <p class="text-sm text-yellow-700 mt-1">
                                                    Pengerjaan assessment tidak bisa dilakukan sampai sesi diaktifkan oleh admin
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                @endif
                
                @if($sesi->assessments->count() > 0)
                    <div class="space-y-4">
                        @foreach($sesi->assessments->sortBy('urutan') as $assessment)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                                {{ $assessment->urutan }}
                                            </span>
                                            <h4 class="text-lg font-medium text-gray-900">{{ $assessment->penilaian->nama }}</h4>
                                        </div>
                                        
                                        <div class="ml-11 space-y-2">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Jenis:</span> 
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                                    @if($assessment->penilaian->jenis === 'studi_kasus') bg-blue-100 text-blue-800
                                                    @elseif($assessment->penilaian->jenis === 'in_tray') bg-green-100 text-green-800
                                                    @elseif($assessment->penilaian->jenis === 'role_play') bg-purple-100 text-purple-800
                                                    @else bg-orange-100 text-orange-800
                                                    @endif">
                                                    {{ $assessment->penilaian->jenis_text }}
                                                </span>
                                            </p>
                                            
                                            @if($assessment->durasi_menit)
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Durasi:</span> {{ $assessment->durasi_menit }} menit
                                                </p>
                                            @endif
                                            
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Deskripsi:</span> 
                                                {{ $assessment->penilaian->deskripsi ?? 'Tidak ada deskripsi' }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="ml-4">
                                        @if($sesi->status === 'active')
                                            @if($assessment->penilaian->jenis === 'studi_kasus')
                                                <a href="{{ route('peserta.assessment.studi-kasus', $assessment->penilaian->id) }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Mulai Studi Kasus
                                                </a>
                                            @else
                                                <a href="{{ route('peserta.assessment.kerja', $assessment->penilaian->id) }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Mulai
                                                </a>
                                            @endif
                                        @else
                                            <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Belum Aktif
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Assessment</h4>
                        <p class="text-gray-500">Sesi ini belum memiliki assessment yang dapat dikerjakan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('peserta.dashboard') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200 text-center">
                Kembali ke Dashboard
            </a>
            
            @if($sesi->status === 'active')
                <a href="{{ route('peserta.sesi.mulai', $sesi->id) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200 text-center">
                    Mulai Sesi Assessment
                </a>
            @endif
        </div>
    </div>
</body>
</html>
