<header class="admin-topbar">
    <div class="flex items-center gap-3">
        <button type="button" id="sidebarToggle" class="lg:hidden p-2 -ml-2 rounded-lg text-tertiary hover:bg-neutral hover:text-primary" aria-label="Buka menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        @hasSection('topbar_title')
            <h2 class="text-sm font-semibold text-primary hidden sm:block">@yield('topbar_title')</h2>
        @endif
    </div>

    <div class="flex items-center gap-4">
        @yield('topbar_actions')
    </div>
</header>
