<div class="inline-flex items-center justify-center gap-1 flex-wrap shrink-0">
    <a href="{{ route('admin.peserta.show', $peserta->id) }}"
       title="Detail"
       class="p-2 sm:p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-secondary transition-colors">
        <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
    </a>
    <a href="{{ route('admin.peserta.edit', $peserta->id) }}"
       title="Edit"
       class="p-2 sm:p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-primary transition-colors">
        <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
    </a>
    <form action="{{ route('admin.peserta.destroy', $peserta->id) }}"
          method="POST"
          class="delete-peserta-form inline"
          data-nama="{{ $peserta->nama_lengkap }}"
          data-terdaftar="{{ $terdaftarSesi ? '1' : '0' }}"
          data-sesi-names="{{ $sesiTerdaftar->implode('|') }}">
        @csrf
        @method('DELETE')
        <button type="button"
                title="Hapus"
                class="btn-hapus-peserta admin-btn-icon-danger p-2 sm:p-1.5">
            <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    </form>
</div>
