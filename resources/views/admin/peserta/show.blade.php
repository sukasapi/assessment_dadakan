@extends('admin.layouts.app')

@section('title', 'Detail Peserta')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Peserta</h1>
                <p class="text-sm text-gray-600">Informasi lengkap peserta assessment center</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.peserta.edit', $peserta->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Edit Peserta
                </a>
                <a href="{{ route('admin.peserta.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Peserta Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Nama Lengkap</p>
                            <p class="font-medium text-gray-900">{{ $peserta->nama_lengkap }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium text-gray-900">{{ $peserta->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tempat Lahir</p>
                            <p class="font-medium text-gray-900">{{ $peserta->tempat_lahir ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Lahir</p>
                            <p class="font-medium text-gray-900">{{ $peserta->tanggal_lahir ? \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jenis Kelamin</p>
                            <p class="font-medium text-gray-900">{{ $peserta->jenis_kelamin === 'L' ? 'Laki-laki' : ($peserta->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nomor Telepon</p>
                            <p class="font-medium text-gray-900">{{ $peserta->nomor_telepon ?: '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <p class="text-sm text-gray-600">Alamat Rumah</p>
                        <p class="font-medium text-gray-900">{{ $peserta->alamat_rumah ?: '-' }}</p>
                    </div>
                </div>

                <!-- Professional Info -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Profesional</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Instansi</p>
                            <p class="font-medium text-gray-900">{{ $peserta->instansi ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jabatan Saat Ini</p>
                            <p class="font-medium text-gray-900">{{ $peserta->jabatan_saat_ini ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Grade</p>
                            <p class="font-medium text-gray-900">{{ $peserta->grade ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">PIN</p>
                            <p class="font-medium text-gray-900 font-mono">{{ $peserta->pin }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Status Peserta</p>
                            @if($peserta->aktif)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Bergabung Sejak</p>
                            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($peserta->created_at)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.progress.peserta', $peserta->id) }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-center block">
                            Lihat Progress
                        </a>
                        <form method="POST" action="{{ route('admin.peserta.destroy', $peserta->id) }}" class="w-full" onsubmit="return confirmDelete('Apakah Anda yakin ingin menghapus peserta ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                                Hapus Peserta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Summary -->
        @if($peserta->kemajuanPenilaian->count() > 0)
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Progress Assessment</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($progressList as $jenis => $items)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3 capitalize">{{ str_replace('_', ' ', $jenis) }}</h4>
                            <div class="space-y-2">
                                @foreach($items as $progress)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">{{ $progress->penilaian->nama }}</span>
                                    @php
                                        $statusColors = [
                                            'belum_mulai' => 'bg-gray-100 text-gray-800',
                                            'sedang_berlangsung' => 'bg-yellow-100 text-yellow-800',
                                            'selesai' => 'bg-green-100 text-green-800'
                                        ];
                                        $statusColor = $statusColors[$progress->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $progress->status)) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
