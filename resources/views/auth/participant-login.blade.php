<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Peserta - Assessment Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Assessment Awareness</h1>
            <p class="text-gray-600">Login sebagai Peserta</p>
        </div>
          <!-- Disclaimer -->
          <div class="my-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="text-center">
                <h3 class="text-sm font-semibold text-blue-800 mb-2">Disclaimer</h3>
                <p class="text-xs text-blue-700 leading-relaxed">
                Aplikasi ini disusun untuk kebutuhan Competency Advancement Through Assessment Awareness (bukan aplikasi resmi asesment)
                </p>
            </div>
        </div>
        <!-- PIN Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="text-red-600 text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('participant.login') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">
                        Masukkan PIN Anda
                    </label>
                    <input 
                        type="text" 
                        id="pin" 
                        name="pin" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 text-center text-lg tracking-wider font-mono uppercase"
                        placeholder="PIN"
                        maxlength="10"
                        minlength="6"
                        required
                        autocomplete="off"
                        title="PIN akan diberikan oleh penyelenggara"
                    >
                    <p class="text-xs text-gray-500 mt-2 text-center">
                    PIN akan diberikan oleh penyelenggara
                    </p>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200"
                >
                    Masuk ke Assessment
                </button>
            </form>
        </div>
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
            PT LPP Agro Nusantara © 2025

            </p>
        </div>
    </div>

    <script>
        // Auto-format PIN input - fleksibel (angka semua atau kombinasi huruf dan angka)
        document.getElementById('pin').addEventListener('input', function(e) {
            let value = e.target.value;
            // Hanya izinkan huruf dan angka
            value = value.replace(/[^A-Za-z0-9]/g, '');
            // Batasi panjang 6-10 karakter
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            e.target.value = value;
        });
        
        // Validasi sebelum submit - lebih fleksibel
        document.querySelector('form').addEventListener('submit', function(e) {
            const pin = document.getElementById('pin').value;
            // Validasi: minimal 6 karakter, maksimal 10 karakter, hanya huruf dan angka
            const pinRegex = /^[A-Za-z0-9]{6,10}$/;
            
            if (!pinRegex.test(pin)) {
                e.preventDefault();
                alert('PIN harus 6-10 karakter dan hanya boleh mengandung huruf dan angka');
                return false;
            }
        });
    </script>
</body>
</html>
