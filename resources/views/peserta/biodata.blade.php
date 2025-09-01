<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Center - Biodata</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');
        .game-font { font-family: 'Orbitron', monospace; }
        .cyber-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .neon-glow { box-shadow: 0 0 20px rgba(102, 126, 234, 0.5); }
        .cyber-border { border: 2px solid #667eea; }
    </style>
</head>
<body class="cyber-gradient min-h-screen">
    <!-- Header -->
    <header class="bg-black/80 backdrop-blur-sm border-b border-blue-500/30">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('peserta.dashboard') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="game-font text-2xl font-bold text-white">BIODATA PESERTA</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-white font-medium">{{ $peserta->nama_lengkap }}</p>
                        <p class="text-gray-400 text-sm">PIN: {{ $peserta->pin }}</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8">
        <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-8 cyber-border neon-glow">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Personal Information -->
                <div class="space-y-6">
                    <h2 class="game-font text-xl font-bold text-white mb-6 border-b border-blue-500/30 pb-2">
                        INFORMASI PRIBADI
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nama Lengkap (beserta gelar jika ada)</label>
                            <div class="bg-gray-900/50 rounded-lg p-4 cyber-border">
                                <p class="text-white font-medium">{{ $peserta->nama_lengkap }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Tempat Lahir</label>
                            <div class="bg-gray-900/50 rounded-lg p-4 cyber-border">
                                <p class="text-white">{{ $peserta->tempat_lahir ?: 'Tidak diisi' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal Lahir</label>
                            <div class="bg-gray-900/50 rounded-lg p-4 cyber-border">
                                <p class="text-white">{{ $peserta->tanggal_lahir ? $peserta->tanggal_lahir->format('d F Y') : 'Tidak diisi' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Jenis Kelamin</label>
                            <div class="bg-gray-900/50 rounded-lg p-4 cyber-border">
                                <p class="text-white">{{ $peserta->jenis_kelamin_text ?: 'Tidak diisi' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Alamat Rumah</label>
                            <div class="bg-gray-900/50 rounded-lg p-4 cyber-border">
                                <p class="text-white">{{ $peserta->alamat_rumah ?: 'Tidak diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Professional Information -->
                <div class="space-y-6">
                    <h2 class="game-font text-xl font-bold text-white mb-6 border-b border-blue-500/30 pb-2">
                        INFORMASI KONTAK & PROFESIONAL
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Nomor HP/WhatsApp</label>
                            <div class="bg-gray-900/50 rounded-lg p-4 cyber-border">
                                <p class="text-white">{{ $peserta->nomor_telepon ?: 'Tidak diisi' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Alamat E-mail</label>
                            <div class="bg-gray-900/50 rounded-lg p-4 cyber-border">
                                <p class="text-white">{{ $peserta->email ?: 'Tidak diisi' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Asal Instansi/Lembaga/Institusi</label>
                            <div class="bg-gray-900/50 rounded-lg p-4 cyber-border">
                                <p class="text-white">{{ $peserta->instansi ?: 'Tidak diisi' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Jabatan Saat Ini</label>
                            <div class="bg-gray-900/50 rounded-lg p-4 cyber-border">
                                <p class="text-white">{{ $peserta->jabatan_saat_ini ?: 'Tidak diisi' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Grade</label>
                            <div class="bg-gray-900/50 rounded-lg p-4 cyber-border">
                                <p class="text-white">{{ $peserta->grade ?: 'Tidak diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-blue-500/30">
                <div class="flex justify-center">
                    <a href="{{ route('peserta.dashboard') }}" 
                       class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-3 rounded-lg transition-all duration-200 game-font neon-glow">
                        KEMBALI KE DASHBOARD
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
