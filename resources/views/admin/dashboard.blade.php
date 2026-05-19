@extends('admin.layouts.app')

@section('title', 'Dashboard Admin - Assessment Center')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Dashboard Admin',
    'subtitle' => 'Monitoring Assessment Center',
])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="admin-stat-card">
        <p class="stat-label">Total Peserta</p>
        <p class="stat-value">{{ $totalPeserta }}</p>
    </div>
    <div class="admin-stat-card">
        <p class="stat-label">Total Penilaian</p>
        <p class="stat-value">{{ $totalPenilaian }}</p>
    </div>
    <div class="admin-stat-card">
        <p class="stat-label">Peserta Aktif</p>
        <p class="stat-value accent">{{ $pesertaAktif }}</p>
    </div>
</div>

<div class="admin-card p-6 mb-8">
    <h3 class="text-lg font-semibold text-primary mb-4">Progress Assessment</h3>
    <div class="space-y-4">
        @foreach($progressSummary as $jenis => $data)
        <div>
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-primary capitalize">{{ str_replace('_', ' ', $jenis) }}</span>
                <span class="text-sm text-tertiary">{{ $data['total'] }} peserta</span>
            </div>
            @php
                $completed = $data['selesai'];
                $total = $data['total'];
                $percentage = $total > 0 ? ($completed / $total) * 100 : 0;
            @endphp
            <div class="admin-progress-track">
                <div class="admin-progress-fill" style="width: {{ $percentage }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-tertiary mt-1">
                <span>{{ $data['belum_mulai'] }} belum mulai</span>
                <span>{{ $data['sedang_berlangsung'] }} sedang berlangsung</span>
                <span>{{ $data['selesai'] }} selesai</span>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="admin-card overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-[#E2E8F0]">
        <h3 class="text-lg font-semibold text-primary">Aktivitas Terbaru</h3>
    </div>
    <div class="divide-y divide-[#E2E8F0]">
        @forelse($recentActivities as $activity)
        <div class="px-6 py-4">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center min-w-0">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                            <span class="text-xs font-medium text-secondary">{{ substr($activity['type'], 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="ml-4 min-w-0">
                        <p class="text-sm font-medium text-primary truncate">{{ $activity['peserta'] }}</p>
                        <p class="text-sm text-tertiary truncate">{{ $activity['action'] }} - {{ $activity['type'] }}</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm text-primary">{{ \Carbon\Carbon::parse($activity['time'])->format('d/m/Y H:i') }}</p>
                    <span class="admin-badge {{ $activity['status'] === 'final' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ ucfirst($activity['status']) }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-8 text-center">
            <svg class="mx-auto h-12 w-12 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-primary">Belum ada aktivitas</h3>
            <p class="mt-1 text-sm text-tertiary">Aktivitas assessment akan muncul di sini</p>
        </div>
        @endforelse
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="{{ route('admin.peserta.index') }}" class="admin-card p-6 hover:shadow-md transition-shadow block">
        <h3 class="text-lg font-semibold text-primary">Kelola Peserta</h3>
        <p class="text-sm text-tertiary mt-1">Lihat dan edit data peserta</p>
    </a>
    <a href="{{ route('admin.progress.index') }}" class="admin-card p-6 hover:shadow-md transition-shadow block">
        <h3 class="text-lg font-semibold text-primary">Monitor Progress</h3>
        <p class="text-sm text-tertiary mt-1">Lihat progress assessment</p>
    </a>
    <a href="{{ route('admin.progress.export') }}" class="admin-card p-6 hover:shadow-md transition-shadow block">
        <h3 class="text-lg font-semibold text-primary">Export Data</h3>
        <p class="text-sm text-tertiary mt-1">Download laporan progress</p>
    </a>
</div>
@endsection
