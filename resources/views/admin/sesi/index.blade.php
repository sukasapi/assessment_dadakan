@extends('admin.layouts.app')

@section('title', 'Daftar Sesi')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Daftar Sesi',
    'subtitle' => 'Kelola sesi penilaian assessment',
    'actions' => '<a href="' . route('admin.sesi.create') . '" class="admin-btn-primary"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Buat Sesi Baru</a>',
])

@include('admin.partials.alerts')

<div class="admin-card overflow-hidden">
    @if($sesiList->count() > 0)
        <div class="admin-card-table-inner">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-1/4">Nama Sesi</th>
                        <th class="w-1/6">Status</th>
                        <th class="w-1/8">Durasi</th>
                        <th class="w-1/4">Assessment</th>
                        <th class="w-1/8">Dibuat</th>
                        <th class="w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sesiList as $sesi)
                        <tr>
                            <td>
                                <div class="text-sm font-medium text-primary">{{ $sesi->nama }}</div>
                                @if($sesi->catatan)
                                    <div class="text-sm text-tertiary">{{ Str::limit(strip_tags($sesi->catatan), 30) }}</div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap">
                                <select id="status-{{ $sesi->id }}"
                                        class="status-select text-xs font-semibold rounded-full px-3 py-1.5 border-0 focus:ring-2 focus:ring-secondary transition-colors duration-200
                                            @if($sesi->status === 'draft') bg-slate-100 text-slate-700 hover:bg-slate-200
                                            @elseif($sesi->status === 'pending') bg-yellow-100 text-yellow-700 hover:bg-yellow-200
                                            @elseif($sesi->status === 'active') bg-green-100 text-green-700 hover:bg-green-200
                                            @elseif($sesi->status === 'paused') bg-orange-100 text-orange-700 hover:bg-orange-200
                                            @else bg-blue-100 text-blue-700 hover:bg-blue-200
                                            @endif"
                                        data-sesi-id="{{ $sesi->id }}">
                                    <option value="draft" @if($sesi->status === 'draft') selected @endif>Draft</option>
                                    <option value="pending" @if($sesi->status === 'pending') selected @endif>Menunggu</option>
                                    <option value="active" @if($sesi->status === 'active') selected @endif>Aktif</option>
                                    <option value="paused" @if($sesi->status === 'paused') selected @endif>Dijeda</option>
                                    <option value="completed" @if($sesi->status === 'completed') selected @endif>Selesai</option>
                                </select>
                            </td>
                            <td class="text-sm">
                                @if($sesi->durasi_menit)
                                    {{ $sesi->durasi_menit }} menit
                                @else
                                    <span class="text-tertiary">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-sm text-primary mb-2">
                                    {{ $sesi->assessments->count() }} assessment
                                </div>
                                <div class="space-y-2">
                                    @foreach($sesi->assessments as $assessment)
                                        <span class="admin-badge bg-blue-50 text-secondary text-xs">
                                            {{ $assessment->urutan }}. {{ Str::limit(strip_tags($assessment->penilaian->nama), 20) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-sm text-tertiary">
                                {{ $sesi->created_at->format('d/m/Y') }}
                            </td>
                            <td class="align-top text-sm font-medium">
                                <div class="flex flex-col gap-1.5 w-36">
                                    <a href="{{ route('admin.sesi.show', $sesi->id) }}"
                                       class="flex items-center justify-center gap-1 w-full bg-blue-50 hover:bg-blue-100 text-secondary text-xs px-2 py-1.5 rounded-md font-medium transition-colors">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>Lihat</span>
                                    </a>
                                    <a href="{{ route('admin.sesi.peserta', $sesi->id) }}"
                                       class="flex items-center justify-center gap-1 w-full bg-green-50 hover:bg-green-100 text-green-700 text-xs px-2 py-1.5 rounded-md font-medium transition-colors">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <span>Peserta</span>
                                    </a>
                                    <a href="{{ route('admin.progress.answers', ['sesi_id' => $sesi->id]) }}"
                                       class="flex items-center justify-center gap-1 w-full bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs px-2 py-1.5 rounded-md font-medium transition-colors">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <span>Progres</span>
                                    </a>
                                    <a href="{{ route('admin.sesi.edit', $sesi->id) }}"
                                       class="flex items-center justify-center gap-1 w-full bg-slate-100 hover:bg-slate-200 text-primary text-xs px-2 py-1.5 rounded-md font-medium transition-colors">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('admin.sesi.duplicate', $sesi->id) }}"
                                          method="POST"
                                          class="duplicate-sesi-form w-full">
                                        @csrf
                                        <input type="hidden" name="nama" value="">
                                        <button type="button"
                                                class="btn-duplicate-sesi flex items-center justify-center gap-1 w-full bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs px-2 py-1.5 rounded-md font-medium transition-colors"
                                                data-default-nama="{{ \App\Models\SesiPenilaian::suggestDuplicateNama($sesi->nama) }}">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Duplikasi</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.sesi.destroy', $sesi->id) }}"
                                          method="POST"
                                          class="w-full"
                                          onsubmit="return confirmDelete('Apakah Anda yakin ingin menghapus sesi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="flex items-center justify-center gap-1 w-full bg-red-50 hover:bg-red-100 text-danger text-xs px-2 py-1.5 rounded-md font-medium transition-colors">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 px-6">
            <svg class="mx-auto h-12 w-12 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-primary">Belum ada sesi</h3>
            <p class="mt-1 text-sm text-tertiary">Mulai dengan membuat sesi pertama Anda.</p>
            <div class="mt-6">
                <a href="{{ route('admin.sesi.create') }}" class="admin-btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Sesi Baru
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-duplicate-sesi').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const form = this.closest('.duplicate-sesi-form');
            const defaultNama = this.getAttribute('data-default-nama') || '';

            Swal.fire({
                title: 'Duplikasi Sesi',
                html: '<p class="text-sm text-tertiary mb-3">Masukkan judul untuk sesi duplikat beserta konten assessment-nya.</p>',
                input: 'text',
                inputLabel: 'Judul Sesi',
                inputValue: defaultNama,
                inputPlaceholder: 'Nama sesi duplikat',
                showCancelButton: true,
                confirmButtonText: 'Duplikasi',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0F172A',
                cancelButtonColor: '#64748B',
                reverseButtons: true,
                inputValidator: (value) => {
                    if (!value || !value.trim()) {
                        return 'Judul sesi wajib diisi';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.querySelector('input[name="nama"]').value = result.value.trim();
                    form.submit();
                }
            });
        });
    });

    const statusSelects = document.querySelectorAll('.status-select');

    if (statusSelects.length === 0) {
        return;
    }

    statusSelects.forEach((select) => {
        select.setAttribute('data-original-status', select.value);
        updateStatusAppearance(select, select.value);

        select.addEventListener('change', function() {
            const sesiId = this.getAttribute('data-sesi-id');
            const newStatus = this.value;
            const originalStatus = this.getAttribute('data-original-status') || this.value;

            updateStatusAppearance(this, newStatus);

            const statusUrl = '{{ route('admin.sesi.update-status', ['id' => '__SESII__']) }}'.replace('__SESII__', sesiId);
            fetch(statusUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': adminCsrfToken()
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    this.setAttribute('data-original-status', newStatus);
                    updateStatusAppearance(this, newStatus, data.status_label);
                } else {
                    this.value = originalStatus;
                    updateStatusAppearance(this, originalStatus);
                    showNotification(data.message, 'error');
                }
            })
            .catch(() => {
                this.value = originalStatus;
                updateStatusAppearance(this, originalStatus);
                showNotification('Terjadi kesalahan saat mengupdate status.', 'error');
            });
        });
    });

    function updateStatusAppearance(select, status) {
        const classesToRemove = [
            'bg-slate-100', 'text-slate-700', 'hover:bg-slate-200',
            'bg-yellow-100', 'text-yellow-700', 'hover:bg-yellow-200',
            'bg-green-100', 'text-green-700', 'hover:bg-green-200',
            'bg-orange-100', 'text-orange-700', 'hover:bg-orange-200',
            'bg-blue-100', 'text-blue-700', 'hover:bg-blue-200'
        ];
        select.classList.remove(...classesToRemove);
        let newClasses = [];
        switch(status) {
            case 'draft': newClasses = ['bg-slate-100', 'text-slate-700', 'hover:bg-slate-200']; break;
            case 'pending': newClasses = ['bg-yellow-100', 'text-yellow-700', 'hover:bg-yellow-200']; break;
            case 'active': newClasses = ['bg-green-100', 'text-green-700', 'hover:bg-green-200']; break;
            case 'paused': newClasses = ['bg-orange-100', 'text-orange-700', 'hover:bg-orange-200']; break;
            case 'completed': newClasses = ['bg-blue-100', 'text-blue-700', 'hover:bg-blue-200']; break;
            default: newClasses = ['bg-slate-100', 'text-slate-700', 'hover:bg-slate-200'];
        }
        select.classList.add(...newClasses);
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-xl transform transition-all duration-300 ease-in-out translate-x-full opacity-0 ${
            type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' : 'bg-red-500 text-white border-l-4 border-red-600'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.classList.add('translate-x-0', 'opacity-100'), 100);
        setTimeout(() => {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    const flashSuccess = @json(session('success'));
    const flashError = @json(session('error'));
    if (flashSuccess) showNotification(flashSuccess, 'success');
    if (flashError) showNotification(flashError, 'error');
});
</script>
@endpush
