@extends('admin.layouts.app')

@section('title', 'Detail Sesi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.sesi.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $sesi->nama }}</h1>
                    <p class="text-gray-600 mt-2">Detail sesi penilaian assessment</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.sesi.edit', $sesi->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('admin.sesi.destroy', $sesi->id) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirmDelete('Apakah Anda yakin ingin menghapus sesi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Session Information -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Sesi</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
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
                    <dt class="text-sm font-medium text-gray-500">Durasi</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($sesi->durasi_menit)
                            {{ $sesi->durasi_menit }} menit
                        @else
                            <span class="text-gray-400">Tidak ditetapkan</span>
                        @endif
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $sesi->created_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $sesi->updated_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
            </div>
            
            @if($sesi->catatan)
                <div class="mt-6">
                    <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                    <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                        {{ $sesi->catatan }}
                    </dd>
                </div>
            @endif
        </div>
    </div>

    <!-- Assessments -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Assessment dalam Sesi</h3>
            <p class="text-sm text-gray-600 mt-1">Daftar assessment yang akan dijalankan dalam urutan tertentu</p>
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
                                        <h4 class="text-sm font-medium text-gray-900">{{ $assessment->penilaian->nama }}</h4>
                                        <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $assessment->penilaian->jenis)) }}</p>
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
                                        <span class="font-medium text-gray-500">Durasi:</span>
                                        <span class="ml-2 text-gray-900">{{ $assessment->durasi_default }} menit</span>
                                    </div>
                                @endif
                                
                                @if($assessment->instruksi_khusus)
                                    <div class="md:col-span-2">
                                        <span class="font-medium text-gray-500">Instruksi Khusus:</span>
                                        <p class="mt-1 text-gray-900 bg-gray-50 p-2 rounded">{{ $assessment->instruksi_khusus }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada assessment</h3>
                    <p class="mt-1 text-sm text-gray-500">Sesi ini belum memiliki assessment yang ditetapkan.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Participants (if any) -->
    @if($sesi->participants->count() > 0)
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Peserta dalam Sesi</h3>
                <p class="text-sm text-gray-600 mt-1">Daftar peserta yang berpartisipasi dalam sesi ini</p>
            </div>
            <div class="px-6 py-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Peserta
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu Mulai
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu Selesai
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sesi->participants as $participant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $participant->peserta->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-500">{{ $participant->peserta->email }}</div>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($participant->waktu_mulai)
                                            {{ $participant->waktu_mulai->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($participant->waktu_selesai)
                                            {{ $participant->waktu_selesai->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-gray-400">-</span>
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
</div>
@endsection
