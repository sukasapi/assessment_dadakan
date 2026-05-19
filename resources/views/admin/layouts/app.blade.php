<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Assessment Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0F172A',
                        secondary: '#3B82F6',
                        tertiary: '#64748B',
                        neutral: '#F8FAFC',
                        danger: '#EF4444',
                    },
                    fontFamily: {
                        sans: ['Hanken Grotesk', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @php $adminThemeCssV = file_exists(public_path('css/admin-theme.css')) ? filemtime(public_path('css/admin-theme.css')) : time(); @endphp
    <link href="{{ asset('css/admin-theme.css') }}?v={{ $adminThemeCssV }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
    <style>
        body { font-family: 'Hanken Grotesk', sans-serif; }
    </style>
</head>
<body class="bg-neutral font-sans text-primary antialiased">
    <div id="adminSidebarOverlay" class="admin-sidebar-overlay" aria-hidden="true"></div>

    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <div class="flex flex-1 flex-col overflow-hidden min-w-0">
            @include('admin.partials.topbar')

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-neutral">
                @yield('before_content')
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function confirmDelete(message) {
            return confirm(message || 'Apakah Anda yakin ingin menghapus item ini?');
        }

        (function() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminSidebarOverlay');
            const toggle = document.getElementById('sidebarToggle');
            function openSidebar() {
                sidebar?.classList.add('open');
                overlay?.classList.add('open');
            }
            function closeSidebar() {
                sidebar?.classList.remove('open');
                overlay?.classList.remove('open');
            }

            toggle?.addEventListener('click', function() {
                if (sidebar?.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
            overlay?.addEventListener('click', closeSidebar);

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        })();
    </script>
    <script>
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
                onInit: function() {},
                onImageUpload: function(files) {}
            }
        };

        window.initCKEditor = function(elementId) {
            if (window.$ && window.$.fn.summernote && document.getElementById(elementId)) {
                try {
                    if (window.ckeditorInstances && window.ckeditorInstances[elementId]) {
                        return;
                    }
                    if (window.ckeditorInstances && window.ckeditorInstances[elementId]) {
                        $('#' + elementId).summernote('destroy');
                        delete window.ckeditorInstances[elementId];
                    }
                    const editor = $('#' + elementId).summernote(window.summernoteConfig);
                    window.ckeditorInstances = window.ckeditorInstances || {};
                    window.ckeditorInstances[elementId] = editor;
                } catch (error) {}
            }
        };

        window.destroyCKEditor = function(elementId) {
            if (window.$ && window.ckeditorInstances && window.ckeditorInstances[elementId]) {
                try {
                    $('#' + elementId).summernote('destroy');
                    delete window.ckeditorInstances[elementId];
                } catch (error) {}
            }
        };

        $(document).ready(function() {});
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
