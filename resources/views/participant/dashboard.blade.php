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
        .step-active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .step-completed { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .step-pending { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }
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
                    <span class="text-gray-300">Dashboard Peserta</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-white font-medium">{{ $participant->full_name }}</p>
                        <p class="text-gray-400 text-sm">{{ $participant->institution }}</p>
                    </div>
                    <a href="{{ route('participant.biodata') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 game-font">
                        BIODATA
                    </a>
                    <form method="POST" action="{{ route('participant.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg transition-colors duration-200 game-font">
                            LOGOUT
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8">
        @if(!$activeSession)
            <!-- No Active Session -->
            <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-8 cyber-border neon-glow text-center">
                <div class="w-24 h-24 bg-gray-700 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="game-font text-2xl font-bold text-white mb-4">TIDAK ADA SESSION AKTIF</h2>
                <p class="text-gray-300 mb-6">Mohon tunggu hingga admin memulai assessment session.</p>
                <div class="flex items-center justify-center space-x-2 text-gray-400">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse"></div>
                    <span class="game-font text-sm">WAITING FOR ADMIN</span>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse"></div>
                </div>
            </div>
        @else
            <!-- Active Session -->
            <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-8 cyber-border neon-glow mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="game-font text-2xl font-bold text-white mb-2">{{ $activeSession->name }}</h2>
                        <p class="text-gray-300">Session Assessment Aktif</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-green-400 game-font" id="timer">
                            {{ $activeSession->duration_minutes }}:00
                        </div>
                        <p class="text-gray-400 text-sm">Sisa Waktu</p>
                    </div>
                </div>

                <!-- Stepper -->
                <div class="mb-8">
                    <h3 class="game-font text-lg font-bold text-white mb-4">PROGRESS ASSESSMENT</h3>
                    <div class="flex items-center justify-between">
                        @foreach($assessments as $assessment)
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold game-font mb-2
                                    @if(isset($progress[$assessment->id]) && $progress[$assessment->id]->status === 'completed')
                                        step-completed
                                    @elseif(isset($progress[$assessment->id]) && $progress[$assessment->id]->status === 'in_progress')
                                        step-active
                                    @else
                                        step-pending
                                    @endif">
                                    @if(isset($progress[$assessment->id]) && $progress[$assessment->id]->status === 'completed')
                                        ✓
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </div>
                                <span class="text-xs text-gray-300 text-center game-font">{{ $assessment->type_text }}</span>
                            </div>
                            @if(!$loop->last)
                                <div class="flex-1 h-1 bg-gray-600 rounded-full mx-2"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Assessment List -->
                <div class="grid gap-4">
                    @foreach($assessments as $assessment)
                        @php
                            $assessmentProgress = $progress[$assessment->id] ?? null;
                            $canAccess = !$assessmentProgress || $assessmentProgress->status !== 'completed';
                            $isPreviousCompleted = $loop->first || ($loop->index > 0 && isset($progress[$assessments[$loop->index - 1]->id]) && $progress[$assessments[$loop->index - 1]->id]->status === 'completed');
                        @endphp
                        
                        <div class="bg-gray-900/50 rounded-xl p-6 cyber-border">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="game-font text-lg font-bold text-white mb-2">{{ $assessment->name }}</h4>
                                    <p class="text-gray-300 text-sm mb-2">{{ $assessment->instructions }}</p>
                                    <div class="flex items-center space-x-4 text-sm">
                                        <span class="text-gray-400">Durasi: {{ $assessment->duration_minutes }} menit</span>
                                        @if($assessmentProgress)
                                            <span class="text-gray-400">Status: 
                                                <span class="
                                                    @if($assessmentProgress->status === 'completed') text-green-400
                                                    @elseif($assessmentProgress->status === 'in_progress') text-blue-400
                                                    @else text-gray-400
                                                    @endif">
                                                    {{ ucfirst($assessmentProgress->status) }}
                                                </span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    @if($canAccess && ($loop->first || $isPreviousCompleted))
                                        <a href="{{ route('participant.assessment', $assessment->id) }}" 
                                           class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-lg transition-all duration-200 transform hover:scale-105 game-font neon-glow">
                                            @if($assessmentProgress && $assessmentProgress->status === 'in_progress')
                                                LANJUTKAN
                                            @else
                                                MULAI
                                            @endif
                                        </a>
                                    @elseif($assessmentProgress && $assessmentProgress->status === 'completed')
                                        <span class="bg-green-600 text-white px-6 py-3 rounded-lg game-font">SELESAI</span>
                                    @else
                                        <span class="bg-gray-600 text-gray-300 px-6 py-3 rounded-lg game-font cursor-not-allowed">LOCKED</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        // Timer countdown
        @if($activeSession)
            var remainingTime = {{ $activeSession->getRemainingTime() ?? 0 }};
            
            function updateTimer() {
                if (remainingTime <= 0) {
                    window.location.reload();
                    return;
                }
                
                var minutes = Math.floor(remainingTime / 60);
                var seconds = remainingTime % 60;
                document.getElementById('timer').textContent = 
                    minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
                
                remainingTime--;
            }
            
            updateTimer();
            setInterval(updateTimer, 1000);
        @endif
    </script>
</body>
</html>
