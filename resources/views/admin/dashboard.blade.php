@extends('admin.layouts.app')

@section('title', 'Dashboard Admin - Assessment Center')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                    <p class="text-sm text-gray-600">Monitoring Assessment Center</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Welcome, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Peserta -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Peserta</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalPeserta }}</p>
                    </div>
                </div>
            </div>



            <!-- Total Penilaian -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Penilaian</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalPenilaian }}</p>
                    </div>
                </div>
            </div>

            <!-- Peserta Aktif -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Peserta Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $pesertaAktif }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8 mb-8">
            <!-- Progress Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Progress Assessment</h3>
                <div class="space-y-4">
                    @foreach($progressSummary as $jenis => $data)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $jenis) }}</span>
                            <span class="text-sm text-gray-500">{{ $data['total'] }} peserta</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $completed = $data['selesai'];
                                $total = $data['total'];
                                $percentage = $total > 0 ? ($completed / $total) * 100 : 0;
                            @endphp
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>{{ $data['belum_mulai'] }} belum mulai</span>
                            <span>{{ $data['sedang_berlangsung'] }} sedang berlangsung</span>
                            <span>{{ $data['selesai'] }} selesai</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Aktivitas Terbaru</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentActivities as $activity)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-xs font-medium text-blue-600">{{ substr($activity['type'], 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $activity['peserta'] }}</p>
                                <p class="text-sm text-gray-500">{{ $activity['action'] }} - {{ $activity['type'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($activity['time'])->format('d/m/Y H:i') }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity['status'] === 'final' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($activity['status']) }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada aktivitas</h3>
                    <p class="mt-1 text-sm text-gray-500">Aktivitas assessment akan muncul di sini</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.peserta.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Kelola Peserta</h3>
                        <p class="text-sm text-gray-600">Lihat dan edit data peserta</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.progress.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Monitor Progress</h3>
                        <p class="text-sm text-gray-600">Lihat progress assessment</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.progress.export') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Export Data</h3>
                        <p class="text-sm text-gray-600">Download laporan progress</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
