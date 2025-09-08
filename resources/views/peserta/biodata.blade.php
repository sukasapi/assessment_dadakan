<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Center - Biodata Peserta</title>
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
                    <span class="text-gray-600">Biodata Peserta</span>
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Biodata</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Biodata Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Informasi Pribadi</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <p class="text-gray-900 font-medium">{{ $peserta->nama_lengkap }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-gray-900">{{ $peserta->email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <p class="text-gray-900">{{ $peserta->nomor_telepon ?? 'Tidak ada' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <p class="text-gray-900">{{ $peserta->tanggal_lahir ? \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d/m/Y') : 'Tidak ada' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <p class="text-gray-900">{{ $peserta->jenis_kelamin ?? 'Tidak ada' }}</p>
                        </div>
                    </div>
                    
                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instansi/Perusahaan</label>
                            <p class="text-gray-900">{{ $peserta->instansi ?? 'Tidak ada' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan Saat Ini</label>
                            <p class="text-gray-900">{{ $peserta->jabatan_saat_ini ?? 'Tidak ada' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <p class="text-gray-900">{{ $peserta->alamat ?? 'Tidak ada' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PIN</label>
                            <p class="text-gray-900 font-mono">{{ $peserta->pin }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                                @if($peserta->aktif) bg-green-100 text-green-800 border border-green-200
                                @else bg-red-100 text-red-800 border border-red-200
                                @endif">
                                {{ $peserta->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Informasi Tambahan -->
                @if($peserta->pendidikan_terakhir || $peserta->pengalaman_kerja || $peserta->sertifikasi)
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Tambahan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($peserta->pendidikan_terakhir)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                                    <p class="text-gray-900">{{ $peserta->pendidikan_terakhir }}</p>
                                </div>
                            @endif
                            
                            @if($peserta->pengalaman_kerja)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pengalaman Kerja</label>
                                    <p class="text-gray-900">{{ $peserta->pengalaman_kerja }}</p>
                                </div>
                            @endif
                            
                            @if($peserta->sertifikasi)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sertifikasi</label>
                                    <p class="text-gray-900">{{ $peserta->sertifikasi }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Action Buttons -->
            <div class="p-6 bg-gray-50">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('peserta.dashboard') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200 text-center">
                        Kembali ke Dashboard
                    </a>
                    <button type="button" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200">
                        Edit Biodata
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
