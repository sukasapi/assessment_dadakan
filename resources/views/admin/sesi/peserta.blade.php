@extends('admin.layouts.app')

@section('title', 'Kelola Peserta Sesi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Peserta Sesi</h1>
            <p class="text-gray-600 mt-2">
                Sesi: <span class="font-semibold">{{ strip_tags($sesi->nama) }}</span>
            </p>
            <p class="text-sm text-gray-500 mt-1">
                Status: <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    @if($sesi->status === 'draft') bg-gray-100 text-gray-800
                    @elseif($sesi->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($sesi->status === 'active') bg-green-100 text-green-800
                    @elseif($sesi->status === 'paused') bg-orange-100 text-orange-800
                    @else bg-blue-100 text-blue-800
                    @endif">
                    {{ $sesi->status_label }}
                </span>
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.sesi.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                Kembali ke Daftar Sesi
            </a>
            <a href="{{ route('admin.sesi.show', $sesi->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Lihat Detail Sesi
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Daftar Peserta Terdaftar -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    Peserta Terdaftar 
                    <span class="text-sm text-gray-500">({{ $sesi->participants->count() }})</span>
                </h3>
            </div>
            
            @if($sesi->participants->count() > 0)
                <div class="overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach($sesi->participants as $participant)
                            <li class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                                                                         <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                 <span class="text-sm font-medium text-blue-800">
                                                     {{ strtoupper(substr($participant->peserta->nama_lengkap, 0, 2)) }}
                                                 </span>
                                             </div>
                                         </div>
                                         <div class="ml-4">
                                             <div class="text-sm font-medium text-gray-900">
                                                 {{ $participant->peserta->nama_lengkap }}
                                             </div>
                                             <div class="text-sm text-gray-500">
                                                 @if($participant->peserta->instansi)
                                                     {{ $participant->peserta->instansi }}
                                                     @if($participant->peserta->jabatan_saat_ini)
                                                         / {{ $participant->peserta->jabatan_saat_ini }}
                                                     @endif
                                                 @elseif($participant->peserta->jabatan_saat_ini)
                                                     {{ $participant->peserta->jabatan_saat_ini }}
                                                 @else
                                                     <span class="text-gray-400">-</span>
                                                 @endif
                                             </div>
                                             <div class="text-xs text-gray-400">
                                                 PIN: {{ $participant->peserta->pin ?? 'Tidak ada' }}
                                             </div>
                                             <div class="text-xs text-gray-400 mt-1">
                                                 Status: 
                                                 <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                     @if($participant->status === 'aktif') bg-green-100 text-green-800
                                                     @elseif($participant->status === 'nonaktif') bg-red-100 text-red-800
                                                     @elseif($participant->status === 'selesai') bg-blue-100 text-blue-800
                                                     @else bg-gray-100 text-gray-800
                                                     @endif">
                                                     {{ $participant->status_label }}
                                                 </span>
                                             </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($participant->waktu_mulai)
                                            <span class="text-xs text-gray-500">
                                                Mulai: {{ \Carbon\Carbon::parse($participant->waktu_mulai)->format('H:i') }}
                                            </span>
                                        @endif
                                        <form action="{{ route('admin.sesi.peserta.destroy', [$sesi->id, $participant->peserta_id]) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta ini dari sesi?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 text-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada peserta</h3>
                    <p class="mt-1 text-sm text-gray-500">Daftarkan peserta pertama untuk sesi ini.</p>
                </div>
            @endif
        </div>

        <!-- Form Daftarkan Peserta Baru -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Daftarkan Peserta Baru</h3>
            </div>
            
            <div class="px-6 py-4">
                @if($availablePeserta->count() > 0)
                    <form action="{{ route('admin.sesi.peserta.store', $sesi->id) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="peserta_ids" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Peserta
                                </label>
                                <select name="peserta_ids[]" id="peserta_ids" multiple 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        size="8">
                                                                         @foreach($availablePeserta as $peserta)
                                         <option value="{{ $peserta->id }}">
                                             {{ $peserta->nama_lengkap }} 
                                             @if($peserta->instansi && $peserta->jabatan_saat_ini)
                                                 ({{ $peserta->instansi }}/{{ $peserta->jabatan_saat_ini }})
                                             @elseif($peserta->instansi)
                                                 ({{ $peserta->instansi }})
                                             @elseif($peserta->jabatan_saat_ini)
                                                 ({{ $peserta->jabatan_saat_ini }})
                                             @endif
                                         </option>
                                     @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">
                                    Gunakan Ctrl+Click (atau Cmd+Click di Mac) untuk memilih multiple peserta
                                </p>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                    Daftarkan Peserta
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada peserta tersedia</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Semua peserta aktif sudah terdaftar di sesi ini atau tidak ada peserta aktif.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('admin.peserta.index') }}" 
                               class="text-blue-600 hover:text-blue-500 text-sm">
                                Kelola Peserta
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Informasi Sesi -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Sesi</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Assessment</h4>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $sesi->assessments->count() }} assessment
                    </p>
                    <div class="mt-2 space-y-1">
                        @foreach($sesi->assessments->sortBy('urutan') as $assessment)
                            <div class="text-xs text-gray-600">
                                {{ $assessment->urutan }}. {{ strip_tags($assessment->penilaian->nama) }}
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Durasi</h4>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($sesi->durasi_menit)
                            {{ $sesi->durasi_menit }} menit
                        @else
                            Tidak ditetapkan
                        @endif
                    </p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Dibuat</h4>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $sesi->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
