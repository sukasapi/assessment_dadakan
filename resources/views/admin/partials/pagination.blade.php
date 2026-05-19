@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
    $label = $label ?? 'hasil';
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
    $start = max(1, $current - 2);
    $end = min($last, $current + 2);
@endphp

<div class="admin-pagination">
    <div class="flex-1 flex justify-between gap-3 sm:hidden">
        @if ($paginator->onFirstPage())
            <span class="admin-pagination-btn disabled">Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="admin-pagination-btn">Sebelumnya</a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="admin-pagination-btn">Selanjutnya</a>
        @else
            <span class="admin-pagination-btn disabled">Selanjutnya</span>
        @endif
    </div>

    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between sm:gap-4">
        <p class="admin-pagination-info">
            Menampilkan
            <span class="font-semibold text-primary">{{ $paginator->firstItem() ?? 0 }}</span>
            sampai
            <span class="font-semibold text-primary">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="font-semibold text-primary">{{ $paginator->total() }}</span>
            {{ $label }}
        </p>

        @if ($paginator->hasPages())
        <nav class="admin-pagination-nav" aria-label="Navigasi halaman">
            @if ($paginator->onFirstPage())
                <span class="admin-pagination-btn disabled" aria-hidden="true">&lsaquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="admin-pagination-btn" aria-label="Halaman sebelumnya">&lsaquo;</a>
            @endif

            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" class="admin-pagination-btn">1</a>
                @if ($start > 2)
                    <span class="admin-pagination-ellipsis">…</span>
                @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <span class="admin-pagination-btn active" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="admin-pagination-btn">{{ $page }}</a>
                @endif
            @endfor

            @if ($end < $last)
                @if ($end < $last - 1)
                    <span class="admin-pagination-ellipsis">…</span>
                @endif
                <a href="{{ $paginator->url($last) }}" class="admin-pagination-btn">{{ $last }}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="admin-pagination-btn" aria-label="Halaman berikutnya">&rsaquo;</a>
            @else
                <span class="admin-pagination-btn disabled" aria-hidden="true">&rsaquo;</span>
            @endif
        </nav>
        @endif
    </div>
</div>
