@extends('admin.layouts.app')

@section('title', 'Daftar Progres Peserta')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Progres Pengerjaan Peserta</h1>

    <!-- Export CSV -->
    <div class="mb-4 flex items-center gap-3">
        <label class="text-sm text-gray-700">Delimiter:</label>
        <select id="csvDelimiter" class="border-gray-300 rounded-md text-sm">
            <option value=",">Koma (,)</option>
            <option value=";">Titik koma (;)</option>
        </select>
        <a id="exportCsvBtn" href="#" class="bg-green-600 text-white px-3 py-2 rounded-md text-sm hover:bg-green-700">Export CSV</a>
    </div>

    <!-- Tabel Progres Ringkas -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Sesi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Studi Kasus</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">In‑Tray</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role‑Play</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LGD/FGD</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    @php $row = 1; @endphp
                    @foreach(\App\Models\SesiPenilaian::with(['participants.peserta','assessments.penilaian'])->orderBy('created_at','desc')->get() as $sesi)
                        @foreach($sesi->participants as $part)
                            @php
                                $peserta = $part->peserta;
                                $mapJenis = ['studi_kasus'=>null,'in_tray'=>null,'roleplay'=>null,'role_play'=>null,'fgd'=>null];
                                foreach($sesi->assessments as $sa){ $mapJenis[$sa->penilaian->jenis] = $sa->penilaian->id; }
                                $statusBadge = function($penilaianId) use($peserta){
                                    if(!$penilaianId) return '<span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600 border">tidak tersedia</span>';
                                    $prog = \App\Models\KemajuanPenilaian::where('peserta_id',$peserta->id)->where('penilaian_id',$penilaianId)->first();
                                    $status = $prog->status ?? 'belum';
                                    $color = match($status){
                                        'sedang_berlangsung' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'selesai' => 'bg-green-100 text-green-800 border-green-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200'
                                    };
                                    $text = $status === 'sedang_berlangsung' ? 'draft' : ($status === 'selesai' ? 'selesai' : 'belum');
                                    return "<span class=\"px-2 py-0.5 rounded-full text-xs {$color} border\">{$text}</span>";
                                };
                            @endphp
                            <tr>
                                <td class="px-4 py-2">{{ $row++ }}</td>
                                <td class="px-4 py-2">{{ $sesi->nama }}</td>
                                <td class="px-4 py-2">{{ $peserta->nama_lengkap }}</td>
                                <td class="px-4 py-2">{{ $peserta->instansi ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $peserta->jabatan ?? '-' }}</td>
                                <td class="px-4 py-2">{!! $statusBadge($mapJenis['studi_kasus']) !!}</td>
                                <td class="px-4 py-2">{!! $statusBadge($mapJenis['in_tray']) !!}</td>
                                <td class="px-4 py-2">{!! $statusBadge($mapJenis['roleplay'] ?? $mapJenis['role_play']) !!}</td>
                                <td class="px-4 py-2">{!! $statusBadge($mapJenis['fgd']) !!}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const btn = document.getElementById('exportCsvBtn');
    const sel = document.getElementById('csvDelimiter');
    if (btn && sel) {
        btn.addEventListener('click', function(e){
            e.preventDefault();
            const d = encodeURIComponent(sel.value);
            window.location.href = '{{ route('admin.progress.export') }}' + '?delimiter=' + d;
        });
    }
});
</script>
@endsection




