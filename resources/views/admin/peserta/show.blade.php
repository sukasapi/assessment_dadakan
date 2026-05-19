@extends('admin.layouts.app')

@section('title', 'Detail Peserta')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Detail Peserta',
    'subtitle' => 'Informasi lengkap peserta assessment center',
    'actions' => '<a href="' . route('admin.peserta.edit', $peserta->id) . '" class="admin-btn-primary">Edit Peserta</a>'
        . '<a href="' . route('admin.peserta.index') . '" class="admin-btn-secondary">Kembali</a>',
])

        <!-- Peserta Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2">
                <div class="admin-card p-6">
                    <h3 class="text-lg font-medium text-primary mb-4">Informasi Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-tertiary">Nama Lengkap</p>
                            <p class="font-medium text-primary">{{ $peserta->nama_lengkap }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-tertiary">Email</p>
                            <p class="font-medium text-primary">{{ $peserta->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-tertiary">Tempat Lahir</p>
                            <p class="font-medium text-primary">{{ $peserta->tempat_lahir ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-tertiary">Tanggal Lahir</p>
                            <p class="font-medium text-primary">{{ $peserta->tanggal_lahir ? \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-tertiary">Jenis Kelamin</p>
                            <p class="font-medium text-primary">{{ $peserta->jenis_kelamin === 'L' ? 'Laki-laki' : ($peserta->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-tertiary">Nomor Telepon</p>
                            <p class="font-medium text-primary">{{ $peserta->nomor_telepon ?: '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <p class="text-sm text-tertiary">Alamat Rumah</p>
                        <p class="font-medium text-primary">{{ $peserta->alamat_rumah ?: '-' }}</p>
                    </div>
                </div>

                <!-- Professional Info -->
                <div class="admin-card p-6 mt-6">
                    <h3 class="text-lg font-medium text-primary mb-4">Informasi Profesional</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-tertiary">Instansi</p>
                            <p class="font-medium text-primary">{{ $peserta->instansi ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-tertiary">Jabatan Saat Ini</p>
                            <p class="font-medium text-primary">{{ $peserta->jabatan_saat_ini ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-tertiary">Grade</p>
                            <p class="font-medium text-primary">{{ $peserta->grade ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-tertiary">PIN</p>
                            <p class="font-medium text-primary font-mono">{{ $peserta->pin }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Status Card -->
                <div class="admin-card p-6">
                    <h3 class="text-lg font-medium text-primary mb-4">Status</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-tertiary">Status Peserta</p>
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
                            <p class="text-sm text-tertiary">Bergabung Sejak</p>
                            <p class="font-medium text-primary">{{ \Carbon\Carbon::parse($peserta->created_at)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="admin-card p-6 mt-6">
                    <h3 class="text-lg font-medium text-primary mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.progress.peserta', $peserta->id) }}" class="w-full admin-btn-primary text-center block">
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
            <div class="admin-card">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-primary">Progress Assessment</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($progressList as $jenis => $items)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-primary mb-3 capitalize">{{ str_replace('_', ' ', $jenis) }}</h4>
                            <div class="space-y-2">
                                @foreach($items as $progress)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-tertiary">{{ $progress->penilaian->nama }}</span>
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
@endsection
