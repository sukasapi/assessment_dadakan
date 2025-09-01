<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Center - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');
        .game-font { font-family: 'Orbitron', monospace; }
        .cyber-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .neon-glow { box-shadow: 0 0 20px rgba(102, 126, 234, 0.5); }
        .cyber-border { border: 2px solid #667eea; }
        .step-pending { background: linear-gradient(135deg, #4b5563 0%, #6b7280 100%); }
        .step-active { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .step-completed { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    </style>
</head>
<body class="cyber-gradient min-h-screen">
    <!-- Header -->
    <header class="bg-black/80 backdrop-blur-sm border-b border-blue-500/30">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="game-font text-2xl font-bold text-white">ASSESSMENT CENTER</h1>
                    <div class="w-1 h-8 bg-blue-400 rounded-full"></div>
                    <span class="text-gray-300">DASHBOARD PESERTA</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-white font-medium">{{ $peserta->nama_lengkap }}</p>
                        <p class="text-gray-400 text-sm">{{ $peserta->instansi }}</p>
                    </div>
                    <form method="POST" action="{{ route('peserta.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 game-font">
                            LOGOUT
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8">
        @if($sesiAktif)
            <!-- Active Session Info -->
            <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-8 cyber-border neon-glow mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="game-font text-xl font-bold text-white">{{ $sesiAktif->nama }}</h2>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-green-400 game-font" id="timer">
                            {{ $sesiAktif->durasi_menit }}:00
                        </div>
                        <p class="text-gray-400 text-sm">Sisa Waktu</p>
                    </div>
                </div>
                
                <div class="bg-gray-900/50 rounded-xl p-6 cyber-border">
                    <h3 class="game-font text-lg font-bold text-white mb-4">PROGRESS PENILAIAN</h3>
                    
                    <!-- Stepper -->
                    <div class="flex items-center justify-between">
                        @foreach($penilaian as $pen)
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold game-font mb-2
                                    @if(isset($progress[$pen->id]) && $progress[$pen->id] === 'selesai')
                                        step-completed
                                    @elseif(isset($progress[$pen->id]) && $progress[$pen->id] === 'sedang_berlangsung')
                                        step-active
                                    @else
                                        step-pending
                                    @endif">
                                    @if(isset($progress[$pen->id]) && $progress[$pen->id] === 'selesai')
                                        ✓
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </div>
                                <span class="text-xs text-gray-300 text-center game-font">{{ $pen->jenis_text }}</span>
                            </div>
                            @if(!$loop->last)
                                <div class="flex-1 h-1 bg-gray-600 rounded-full mx-2"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Assessment List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($penilaian as $pen)
                    <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-6 cyber-border neon-glow">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="game-font text-lg font-bold text-white mb-2">{{ $pen->nama }}</h3>
                                <p class="text-gray-400 text-sm mb-2">{{ $pen->jenis_text }}</p>
                                <p class="text-gray-300 text-sm">Durasi: {{ $pen->durasi_menit }} menit</p>
                            </div>
                            <div class="text-right">
                                @if(isset($progress[$pen->id]))
                                    @if($progress[$pen->id] === 'selesai')
                                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs game-font">SELESAI</span>
                                    @elseif($progress[$pen->id] === 'sedang_berlangsung')
                                        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs game-font">LANJUTKAN</span>
                                    @endif
                                @else
                                    <span class="bg-gray-600 text-white px-3 py-1 rounded-full text-xs game-font">LOCKED</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            @if(isset($progress[$pen->id]))
                                @if($progress[$pen->id] === 'selesai')
                                    <a href="{{ route('peserta.penilaian', $pen->id) }}" 
                                       class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded-lg transition-colors duration-200 game-font">
                                       LIHAT HASIL
                                    </a>
                                @elseif($progress[$pen->id] === 'sedang_berlangsung')
                                    <a href="{{ route('peserta.penilaian', $pen->id) }}" 
                                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg transition-colors duration-200 game-font">
                                       LANJUTKAN
                                    </a>
                                @endif
                            @else
                                @if($loop->first || (isset($progress[$penilaian[$loop->index-1]->id]) && $progress[$penilaian[$loop->index-1]->id] === 'selesai'))
                                    <a href="{{ route('peserta.penilaian', $pen->id) }}" 
                                       class="block w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white text-center py-2 rounded-lg transition-all duration-200 game-font neon-glow">
                                       MULAI
                                    </a>
                                @else
                                    <button disabled class="block w-full bg-gray-600 text-gray-400 text-center py-2 rounded-lg cursor-not-allowed game-font">
                                        LOCKED
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Active Session -->
            <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-8 cyber-border neon-glow text-center">
                <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="game-font text-xl font-bold text-white mb-4">TIDAK ADA SESI AKTIF</h2>
                <p class="text-gray-300 mb-6">Saat ini tidak ada sesi penilaian yang sedang berlangsung. Silakan tunggu hingga admin memulai sesi baru.</p>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="mt-8 text-center">
            <a href="{{ route('peserta.biodata') }}" 
               class="inline-flex items-center space-x-2 bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors duration-200 game-font neon-glow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>LIHAT BIODATA</span>
            </a>
        </div>
    </div>

    <script>
        // Timer countdown
        var remainingTime = {{ $sesiAktif ? $sesiAktif->getRemainingTime() : 0 }};
        
        function updateTimer() {
            if (remainingTime <= 0) {
                document.getElementById('timer').textContent = '00:00';
                return;
            }
            
            var minutes = Math.floor(remainingTime / 60);
            var seconds = remainingTime % 60;
            document.getElementById('timer').textContent = 
                minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
            
            remainingTime--;
        }
        
        if (remainingTime > 0) {
            updateTimer();
            setInterval(updateTimer, 1000);
        }
    </script>
</body>
</html>
