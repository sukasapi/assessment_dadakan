@php
    $title = $title ?? '';
    $subtitle = $subtitle ?? null;
@endphp
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">{{ $title }}</h1>
        @if($subtitle)
            <p class="admin-page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actions) && trim($actions) !== '')
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            {!! $actions !!}
        </div>
    @endif
</div>
