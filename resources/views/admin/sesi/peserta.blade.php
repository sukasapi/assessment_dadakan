@extends('admin.layouts.app')

@section('title', 'Kelola Peserta Sesi')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Kelola Peserta Sesi',
    'subtitle' => 'Sesi: ' . strip_tags($sesi->nama) . ' · Status: ' . $sesi->status_label,
    'actions' => '<a href="' . route('admin.sesi.index') . '" class="admin-btn-secondary">Kembali ke Daftar Sesi</a>'
        . '<a href="' . route('admin.sesi.show', $sesi->id) . '" class="admin-btn-primary">Lihat Detail Sesi</a>',
])

@include('admin.partials.alerts')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Daftar Peserta Terdaftar -->
        <div class="admin-card">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-primary">
                    Peserta Terdaftar 
                    <span class="text-sm text-tertiary">({{ $sesi->participants->count() }})</span>
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
                                             <div class="text-sm font-medium text-primary">
                                                 {{ $participant->peserta->nama_lengkap }}
                                             </div>
                                             <div class="text-sm text-tertiary">
                                                 @if($participant->peserta->instansi)
                                                     {{ $participant->peserta->instansi }}
                                                     @if($participant->peserta->jabatan_saat_ini)
                                                         / {{ $participant->peserta->jabatan_saat_ini }}
                                                     @endif
                                                 @elseif($participant->peserta->jabatan_saat_ini)
                                                     {{ $participant->peserta->jabatan_saat_ini }}
                                                 @else
                                                     <span class="text-tertiary">-</span>
                                                 @endif
                                             </div>
                                             <div class="text-xs text-tertiary">
                                                 PIN: {{ $participant->peserta->pin ?? 'Tidak ada' }}
                                             </div>
                                             <div class="text-xs text-tertiary mt-1">
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
                                            <span class="text-xs text-tertiary">
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
                    <svg class="mx-auto h-12 w-12 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-primary">Belum ada peserta</h3>
                    <p class="mt-1 text-sm text-tertiary">Daftarkan peserta pertama untuk sesi ini.</p>
                </div>
            @endif
        </div>

        <!-- Form Daftarkan Peserta Baru -->
        <div class="admin-card">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-primary">Daftarkan Peserta Baru</h3>
            </div>
            
            <div class="px-6 py-4">
                <form action="{{ route('admin.sesi.peserta', $sesi->id) }}" method="GET" class="mb-4" id="search-form">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:space-x-3 space-y-3 sm:space-y-0">
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-primary">
                                Cari Peserta
                            </label>
                            <input type="text" name="search" id="search"
                                   value="{{ request('search', $search ?? '') }}"
                                   placeholder="Cari nama, instansi, jabatan, atau PIN"
                                   class="mt-1 w-full admin-input">
                            <p class="text-xs text-tertiary mt-1">Pencarian otomatis setelah 3 karakter atau saat dikosongkan.</p>
                        </div>
                        @if(request('search'))
                            <div>
                                <a href="{{ route('admin.sesi.peserta', $sesi->id) }}"
                                   class="admin-btn-secondary">
                                    Reset
                                </a>
                            </div>
                        @endif
                    </div>
                    @if($search)
                        <p class="text-sm text-tertiary mt-2">
                            Menampilkan {{ $availablePeserta->count() }} hasil untuk "{{ $search }}"
                        </p>
                    @endif
                </form>

                @if($availablePeserta->count() > 0)
                    <form action="{{ route('admin.sesi.peserta.store', $sesi->id) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="peserta_ids" class="block text-sm font-medium text-primary mb-2">
                                    Pilih Peserta
                                </label>
                                <select name="peserta_ids[]" id="peserta_ids" multiple 
                                        class="w-full admin-input"
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
                                <p class="mt-1 text-sm text-tertiary">
                                    Gunakan Ctrl+Click (atau Cmd+Click di Mac) untuk memilih multiple peserta
                                </p>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="admin-btn-primary">
                                    Daftarkan Peserta
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-primary">Tidak ada peserta tersedia</h3>
                        <p class="mt-1 text-sm text-tertiary">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search');
    const form = document.getElementById('search-form');
    if (!input || !form) return;

    let debounceId;
    const submitForm = () => form.submit();

    input.addEventListener('input', function () {
        const value = this.value.trim();
        clearTimeout(debounceId);

        if (value.length === 0) {
            debounceId = setTimeout(submitForm, 200);
            return;
        }

        if (value.length < 3) return;

        debounceId = setTimeout(submitForm, 300);
    });
});
</script>

    <!-- Informasi Sesi -->
    <div class="mt-8 admin-card">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-primary">Informasi Sesi</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-tertiary">Assessment</h4>
                    <p class="mt-1 text-sm text-primary">
                        {{ $sesi->assessments->count() }} assessment
                    </p>
                    <div class="mt-2 space-y-1">
                        @foreach($sesi->assessments->sortBy('urutan') as $assessment)
                            <div class="text-xs text-tertiary">
                                {{ $assessment->urutan }}. {{ strip_tags($assessment->penilaian->nama) }}
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-tertiary">Durasi</h4>
                    <p class="mt-1 text-sm text-primary">
                        @if($sesi->durasi_menit)
                            {{ $sesi->durasi_menit }} menit
                        @else
                            Tidak ditetapkan
                        @endif
                    </p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-tertiary">Dibuat</h4>
                    <p class="mt-1 text-sm text-primary">
                        {{ $sesi->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
@endsection
