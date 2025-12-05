<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Assessment Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote CDN (stabil dan mudah) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Summernote Styling */
        .note-editor {
            border-radius: 0.375rem !important;
            border: 1px solid #d1d5db !important;
        }
        .note-toolbar {
            border-radius: 0.375rem 0.375rem 0 0 !important;
            border-bottom: 1px solid #d1d5db !important;
            background-color: #f9fafb !important;
        }
        .note-editable {
            border-radius: 0 0 0.375rem 0.375rem !important;
            min-height: 200px !important;
            font-family: 'Inter', sans-serif !important;
            padding: 15px !important;
        }
        /* Fix untuk bullet dan numbering yang tidak muncul */
        .note-editable ul { 
            list-style: disc !important; 
            list-style-position: outside !important; 
            margin-left: 1.5rem !important; 
            padding-left: 0 !important; 
        }
        .note-editable ol { 
            list-style: decimal !important; 
            list-style-position: outside !important; 
            margin-left: 1.5rem !important; 
            padding-left: 0 !important; 
        }
        .note-editable li {
            display: list-item !important;
            margin: 0.25rem 0 !important;
        }
        .note-editable ul li::marker,
        .note-editable ol li::marker {
            display: block !important;
            visibility: visible !important;
        }
        /* Summernote toolbar styling */
        .note-toolbar .btn {
            border-radius: 0.25rem !important;
            margin: 2px !important;
        }
        .note-toolbar .btn:hover {
            background-color: #e5e7eb !important;
        }
        .note-toolbar .btn.active {
            background-color: #dbeafe !important;
            color: #1e40af !important;
        }
        /* Summernote dropdown styling */
        .note-dropdown-menu {
            border-radius: 0.375rem !important;
            border: 1px solid #d1d5db !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
        /* Summernote image styling */
        .note-editable img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 0.25rem !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }
        .note-editable img:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
        /* Summernote popover styling */
        .note-popover {
            border-radius: 0.375rem !important;
            border: 1px solid #d1d5db !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
        .note-popover .popover-content {
            padding: 8px !important;
        }
        .note-popover .btn {
            margin: 2px !important;
            border-radius: 0.25rem !important;
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
    <script>
        // Global Summernote configuration
        window.summernoteConfig = {
            height: 300,
            lang: 'id-ID',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            placeholder: 'Tulis konten di sini...',
            dialogsInBody: true,
            disableDragAndDrop: true,
            disableResizeEditor: true,
            // Konfigurasi untuk gambar
            popover: {
                image: [
                    ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                    ['float', ['floatLeft', 'floatRight', 'floatNone']],
                    ['remove', ['removeMedia']]
                ],
                link: [
                    ['link', ['linkDialogShow', 'unlink']]
                ],
                table: [
                    ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                    ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
                ],
                air: [
                    ['color', ['color']],
                    ['font', ['bold', 'underline', 'clear']]
                ]
            },
            callbacks: {
                onInit: function() {
                    // Summernote initialized
                },
                onImageUpload: function(files) {
                    // Jika ingin upload file, bisa ditambahkan logic di sini
                }
            }
        };
        
        // Function to initialize Summernote
        window.initCKEditor = function(elementId) {
            if (window.$ && window.$.fn.summernote && document.getElementById(elementId)) {
                try {
                    // Check if already initialized
                    if (window.ckeditorInstances && window.ckeditorInstances[elementId]) {
                        return;
                    }
                    
                    // Destroy existing instance if any
                    if (window.ckeditorInstances && window.ckeditorInstances[elementId]) {
                        $('#' + elementId).summernote('destroy');
                        delete window.ckeditorInstances[elementId];
                    }
                    
                    const editor = $('#' + elementId).summernote(window.summernoteConfig);
                    window.ckeditorInstances = window.ckeditorInstances || {};
                    window.ckeditorInstances[elementId] = editor;
                } catch (error) {
                    // Silent fail
                }
            }
        };
        
        // Function to destroy Summernote instance
        window.destroyCKEditor = function(elementId) {
            if (window.$ && window.ckeditorInstances && window.ckeditorInstances[elementId]) {
                try {
                    $('#' + elementId).summernote('destroy');
                    delete window.ckeditorInstances[elementId];
                } catch (error) {
                    // Silent fail
                }
            }
        };
        
        // Initialize Summernote when DOM is ready
        $(document).ready(function() {
            // This will be called by individual pages
        });
    </script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>
