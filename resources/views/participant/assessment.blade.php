<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Center - {{ $assessment->name }}</title>
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
                    <a href="{{ route('participant.dashboard') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="game-font text-2xl font-bold text-white">{{ $assessment->name }}</h1>
                    <div class="w-1 h-8 bg-blue-400 rounded-full"></div>
                    <span class="text-gray-300">{{ $assessment->type_text }}</span>
                </div>
                <div class="text-right">
                    <div class="text-xl font-bold text-green-400 game-font" id="timer">
                        {{ $assessment->duration_minutes }}:00
                    </div>
                    <p class="text-gray-400 text-sm">Sisa Waktu</p>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8">
        <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-8 cyber-border neon-glow">
            <!-- Instructions -->
            <div class="mb-8">
                <h2 class="game-font text-xl font-bold text-white mb-4">PETUNJUK PENGISIAN</h2>
                <div class="bg-gray-900/50 rounded-xl p-6 cyber-border">
                    <p class="text-gray-300 leading-relaxed">
                        {{ $assessment->instructions ?: 'Lorem Ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}
                    </p>
                </div>
            </div>

            @if($assessment->type === 'case_study')
                <!-- Case Study Form -->
                <form id="caseStudyForm" class="space-y-6">
                    <div>
                        <h3 class="game-font text-lg font-bold text-white mb-4">DESKRIPSI SOAL</h3>
                        <div class="bg-gray-900/50 rounded-xl p-6 cyber-border min-h-32">
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-blue-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div class="text-gray-300 leading-relaxed">
                                    {{ $assessment->content ?: 'Deskripsi soal studi kasus akan ditampilkan di sini. Peserta diminta untuk menganalisis situasi yang diberikan dan memberikan jawaban yang komprehensif berdasarkan pemahaman dan pengalaman yang dimiliki.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="game-font text-lg font-bold text-white mb-4">JAWABAN ANDA</h3>
                        <textarea 
                            id="answer" 
                            name="answer" 
                            rows="12" 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition-all duration-200 resize-none"
                            placeholder="Tuliskan jawaban Anda di sini..."
                            required
                        ></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-6">
                        <button 
                            type="button" 
                            onclick="saveAnswer('draft')"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg transition-colors duration-200 game-font neon-glow"
                        >
                            SIMPAN SEMENTARA
                        </button>
                        <button 
                            type="button" 
                            onclick="saveAnswer('final')"
                            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-3 rounded-lg transition-all duration-200 game-font neon-glow"
                        >
                            SIMPAN FINAL
                        </button>
                    </div>
                </form>

            @elseif($assessment->type === 'in_tray')
                <!-- In-Tray Exercise Form -->
                <form id="inTrayForm" class="space-y-6">
                    <div>
                        <h3 class="game-font text-lg font-bold text-white mb-4">MEMO-MEMO</h3>
                        <div class="space-y-4" id="memoContainer">
                            <!-- Memos will be loaded here -->
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-6">
                        <button 
                            type="button" 
                            onclick="saveInTrayAnswer('draft')"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg transition-colors duration-200 game-font neon-glow"
                        >
                            SIMPAN SEMENTARA
                        </button>
                        <button 
                            type="button" 
                            onclick="saveInTrayAnswer('final')"
                            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-3 rounded-lg transition-all duration-200 game-font neon-glow"
                        >
                            SIMPAN FINAL
                        </button>
                    </div>
                </form>

            @elseif($assessment->type === 'roleplay')
                <!-- Role-Play Form -->
                <form id="roleplayForm" class="space-y-6">
                    <div>
                        <h3 class="game-font text-lg font-bold text-white mb-4">INSTRUKSI ROLE-PLAY</h3>
                        <div class="bg-gray-900/50 rounded-xl p-6 cyber-border min-h-32">
                            <div class="text-gray-300 leading-relaxed">
                                {{ $assessment->content ?: 'Instruksi untuk role-play akan ditampilkan di sini. Peserta diminta untuk memahami skenario yang diberikan dan mempersiapkan diri untuk melakukan role-play sesuai dengan instruksi yang ada.' }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="game-font text-lg font-bold text-white mb-4">CATATAN ANDA</h3>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            rows="8" 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition-all duration-200 resize-none"
                            placeholder="Tuliskan catatan atau persiapan Anda untuk role-play..."
                            required
                        ></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-6">
                        <button 
                            type="button" 
                            onclick="saveRoleplayNote('draft')"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg transition-colors duration-200 game-font neon-glow"
                        >
                            SIMPAN SEMENTARA
                        </button>
                        <button 
                            type="button" 
                            onclick="saveRoleplayNote('final')"
                            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-3 rounded-lg transition-all duration-200 game-font neon-glow"
                        >
                            SIMPAN FINAL
                        </button>
                    </div>
                </form>

            @elseif($assessment->type === 'fgd')
                <!-- FGD Form -->
                <form id="fgdForm" class="space-y-6">
                    <div>
                        <h3 class="game-font text-lg font-bold text-white mb-4">INSTRUKSI FGD</h3>
                        <div class="bg-gray-900/50 rounded-xl p-6 cyber-border min-h-32">
                            <div class="text-gray-300 leading-relaxed">
                                {{ $assessment->content ?: 'Instruksi untuk Forum Group Discussion (FGD) akan ditampilkan di sini. Peserta diminta untuk memahami topik yang akan didiskusikan dan mempersiapkan diri untuk berpartisipasi aktif dalam diskusi kelompok.' }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="game-font text-lg font-bold text-white mb-4">CATATAN ANDA</h3>
                        <textarea 
                            id="fgdNotes" 
                            name="notes" 
                            rows="8" 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition-all duration-200 resize-none"
                            placeholder="Tuliskan catatan atau persiapan Anda untuk FGD..."
                            required
                        ></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-6">
                        <button 
                            type="button" 
                            onclick="saveFgdNote('draft')"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg transition-colors duration-200 game-font neon-glow"
                        >
                            SIMPAN SEMENTARA
                        </button>
                        <button 
                            type="button" 
                            onclick="saveFgdNote('final')"
                            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-3 rounded-lg transition-all duration-200 game-font neon-glow"
                        >
                            SIMPAN FINAL
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script>
        // Timer countdown
        var remainingTime = {{ $assessment->getRemainingTime() ?? 0 }};
        
        function updateTimer() {
            if (remainingTime <= 0) {
                // Auto save and redirect
                if (confirm('Waktu habis! Jawaban akan disimpan otomatis.')) {
                    window.location.href = '{{ route("participant.dashboard") }}';
                }
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

        // Save functions
        function saveAnswer(status) {
            var answer = document.getElementById('answer').value;
            if (!answer.trim()) {
                alert('Mohon isi jawaban terlebih dahulu.');
                return;
            }

            fetch('{{ route("assessment.case-study.save", $assessment->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    answer: answer,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    if (status === 'final') {
                        window.location.href = '{{ route("participant.dashboard") }}';
                    }
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan jawaban.');
            });
        }

        function saveInTrayAnswer(status) {
            // Implementation for in-tray exercise
            alert('Fitur In-Tray Exercise akan diimplementasikan.');
        }

        function saveRoleplayNote(status) {
            var notes = document.getElementById('notes').value;
            if (!notes.trim()) {
                alert('Mohon isi catatan terlebih dahulu.');
                return;
            }

            fetch('{{ route("assessment.roleplay.save", $assessment->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    notes: notes,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    if (status === 'final') {
                        window.location.href = '{{ route("participant.dashboard") }}';
                    }
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan catatan.');
            });
        }

        function saveFgdNote(status) {
            var notes = document.getElementById('fgdNotes').value;
            if (!notes.trim()) {
                alert('Mohon isi catatan terlebih dahulu.');
                return;
            }

            fetch('{{ route("assessment.fgd.save", $assessment->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    notes: notes,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    if (status === 'final') {
                        window.location.href = '{{ route("participant.dashboard") }}';
                    }
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan catatan.');
            });
        }
    </script>
</body>
</html>
