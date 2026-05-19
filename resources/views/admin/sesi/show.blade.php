@extends('admin.layouts.app')

@section('title', 'Detail Sesi')

@section('content')
@php
    $sesiShowActions = '<a href="' . route('admin.sesi.edit', $sesi->id) . '" class="admin-btn-secondary">Edit</a>'
        . '<form action="' . route('admin.sesi.destroy', $sesi->id) . '" method="POST" class="inline" onsubmit="return confirmDelete(\'Apakah Anda yakin ingin menghapus sesi ini?\')">'
        . '<input type="hidden" name="_token" value="' . csrf_token() . '">'
        . '<input type="hidden" name="_method" value="DELETE">'
        . '<button type="submit" class="admin-btn-secondary text-red-700 border-red-300">Hapus</button></form>';
@endphp
@include('admin.partials.page-header', [
    'title' => $sesi->nama,
    'subtitle' => 'Detail sesi penilaian assessment',
    'actions' => $sesiShowActions,
])

@include('admin.partials.alerts')

    <!-- Session Information -->
    <div class="admin-card mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-primary">Informasi Sesi</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-tertiary">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($sesi->status === 'draft') bg-gray-100 text-gray-800
                            @elseif($sesi->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($sesi->status === 'active') bg-green-100 text-green-800
                            @elseif($sesi->status === 'paused') bg-orange-100 text-orange-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ $sesi->status_label }}
                        </span>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-tertiary">Durasi</dt>
                    <dd class="mt-1 text-sm text-primary">
                        @if($sesi->durasi_menit)
                            {{ $sesi->durasi_menit }} menit
                        @else
                            <span class="text-tertiary">Tidak ditetapkan</span>
                        @endif
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-tertiary">Dibuat</dt>
                    <dd class="mt-1 text-sm text-primary">
                        {{ $sesi->created_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-tertiary">Terakhir Diupdate</dt>
                    <dd class="mt-1 text-sm text-primary">
                        {{ $sesi->updated_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
            </div>
            
            @if($sesi->catatan)
                <div class="mt-6">
                    <dt class="text-sm font-medium text-tertiary">Catatan</dt>
                    <dd class="mt-1 text-sm text-primary bg-gray-50 p-3 rounded-md">
                        {!! $sesi->catatan !!}
                    </dd>
                </div>
            @endif
        </div>
    </div>

    <!-- Assessments -->
    <div class="admin-card mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-primary">Assessment dalam Sesi</h3>
            <p class="text-sm text-tertiary mt-1">Daftar assessment yang akan dijalankan dalam urutan tertentu</p>
        </div>
        <div class="px-6 py-4">
            @if($sesi->assessments->count() > 0)
                <div class="space-y-4">
                    @foreach($sesi->assessments->sortBy('urutan') as $assessment)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 text-sm font-medium rounded-full mr-3">
                                        {{ $assessment->urutan }}
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-medium text-primary">{{ $assessment->penilaian->nama }}</h4>
                                        <p class="text-xs text-tertiary">{{ ucfirst(str_replace('_', ' ', $assessment->penilaian->jenis)) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($assessment->aktif)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                @if($assessment->durasi_default)
                                    <div>
                                        <span class="font-medium text-tertiary">Durasi:</span>
                                        <span class="ml-2 text-primary">{{ $assessment->durasi_default }} menit</span>
                                    </div>
                                @endif
                                
                                @if($assessment->instruksi_khusus)
                                    <div class="md:col-span-2">
                                        <span class="font-medium text-tertiary">Instruksi Khusus:</span>
                                        <div class="mt-1 text-primary bg-gray-50 p-2 rounded prose max-w-none">{!! $assessment->instruksi_khusus !!}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-primary">Belum ada assessment</h3>
                    <p class="mt-1 text-sm text-tertiary">Sesi ini belum memiliki assessment yang ditetapkan.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Participants (if any) -->
    @if($sesi->participants->count() > 0)
        <div class="admin-card">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-primary">Peserta dalam Sesi</h3>
                <p class="text-sm text-tertiary mt-1">Daftar peserta yang berpartisipasi dalam sesi ini</p>
            </div>
            <div class="px-6 py-4">
                <div class="admin-card-table-inner">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">
                                    Nama Peserta
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">
                                    Waktu Mulai
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-tertiary uppercase tracking-wider">
                                    Waktu Selesai
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sesi->participants as $participant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-primary">{{ $participant->peserta->nama_lengkap }}</div>
                                        <div class="text-sm text-tertiary">{{ $participant->peserta->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($participant->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($participant->status === 'active') bg-green-100 text-green-800
                                            @elseif($participant->status === 'completed') bg-blue-100 text-blue-800
                                            @elseif($participant->status === 'paused') bg-orange-100 text-orange-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $participant->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-tertiary">
                                        @if($participant->waktu_mulai)
                                            {{ $participant->waktu_mulai->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-tertiary">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-tertiary">
                                        @if($participant->waktu_selesai)
                                            {{ $participant->waktu_selesai->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-tertiary">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
