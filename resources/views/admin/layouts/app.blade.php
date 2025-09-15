<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Assessment Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Tinggi minimum editor WYSIWYG */
        .ck-editor__editable[role="textbox"] {
            min-height: 320px; /* ~8 baris+toolbar */
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-900">
                        Assessment Center
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                    <a href="{{ route('admin.sesi.index') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Sesi</a>
                    <a href="{{ route('admin.peserta.index') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Peserta</a>
                    <a href="{{ route('admin.progress.index') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Progress</a>
                    <a href="{{ route('admin.assessment-inputs.index') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Input Assessment</a>
                    <a href="{{ route('admin.petunjuk') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Petunjuk Penggunaan</a>
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                            Review
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="{{ route('admin.review.studi-kasus') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Studi Kasus</a>
                            <a href="{{ route('admin.review.in-tray') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">In-Tray</a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Scripts -->
    <script>
        // Add any JavaScript functionality here
        function confirmDelete(message) {
            return confirm(message || 'Apakah Anda yakin ingin menghapus item ini?');
        }
    </script>
    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>
