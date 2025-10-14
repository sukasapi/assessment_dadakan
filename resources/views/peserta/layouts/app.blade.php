<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Permissions-Policy" content="fullscreen=*">
    <title>@yield('title', 'Assessment System')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .prose {
            max-width: none;
        }
        .prose p {
            margin-bottom: 1rem;
        }
        .prose ul {
            list-style-type: disc;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .prose ol {
            list-style-type: decimal;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        /* Fix untuk bullet dan numbering CKEditor yang tidak muncul */
        .ck-content ul { 
            list-style: disc !important; 
            list-style-position: outside !important; 
            margin-left: 1.5rem !important; 
            padding-left: 0 !important; 
        }
        .ck-content ol { 
            list-style: decimal !important; 
            list-style-position: outside !important; 
            margin-left: 1.5rem !important; 
            padding-left: 0 !important; 
        }
        .ck-content li {
            display: list-item !important;
            margin: 0.25rem 0 !important;
        }
        .ck-editor__editable ul li::marker,
        .ck-editor__editable ol li::marker {
            display: block !important;
            visibility: visible !important;
        }
        /* Tinggi minimum editor WYSIWYG */
        .ck-editor__editable[role="textbox"] {
            min-height: 200px;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-900">Assessment System</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    @if(session('peserta_name'))
                        <span class="text-sm text-gray-700">
                            Selamat datang, <span class="font-medium">{{ session('peserta_name') }}</span>
                        </span>
                        <a href="{{ route('peserta.dashboard') }}" 
                           class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Dashboard
                        </a>
                     <!--   <a href="{{ route('peserta.petunjuk') }}" 
                           class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Petunjuk Penggunaan
                        </a>-->
                        <form action="{{ route('peserta.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} IT LPP Agro Nusantara DEV 2</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
