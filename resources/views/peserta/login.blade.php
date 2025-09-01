<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Center - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');
        .game-font { font-family: 'Orbitron', monospace; }
        .cyber-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .neon-glow { box-shadow: 0 0 20px rgba(102, 126, 234, 0.5); }
        .cyber-border { border: 2px solid #667eea; }
    </style>
</head>
<body class="cyber-gradient min-h-screen flex items-center justify-center p-4">
    <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-8 w-full max-w-md cyber-border neon-glow">
        <div class="text-center mb-8">
            <h1 class="game-font text-3xl font-bold text-white mb-2">ASSESSMENT CENTER</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-400 to-purple-500 mx-auto rounded-full"></div>
        </div>

        <div class="bg-gray-900/50 rounded-xl p-6 mb-6 cyber-border">
            <h2 class="game-font text-xl font-bold text-white mb-4 text-center">DISCLAIMER</h2>
            <p class="text-gray-300 text-sm text-center leading-relaxed">
                Aplikasi ini disusun untuk kebutuhan <strong>Competency Advancement Through Assessment Awareness</strong>
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-900/50 border border-red-500 rounded-lg p-4 mb-6">
                <div class="text-red-400 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('peserta.login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="pin" class="game-font block text-sm font-medium text-gray-300 mb-2">
                    MASUKKAN PIN
                </label>
                <input 
                    type="text" 
                    id="pin" 
                    name="pin" 
                    maxlength="6"
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition-all duration-200 game-font text-center text-lg tracking-widest"
                    placeholder="••••••"
                    required
                    autocomplete="off"
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 game-font text-lg neon-glow"
            >
                LOGIN
            </button>
        </form>

        <div class="mt-8 text-center">
            <div class="flex items-center justify-center space-x-2 text-gray-400 text-sm">
                <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                <span class="game-font">SYSTEM READY</span>
                <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus pada input PIN
        document.getElementById('pin').focus();
        
        // Auto-uppercase dan hanya angka
        document.getElementById('pin').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
        });
    </script>
</body>
</html>
