@extends('admin.layouts.app')

@section('title', 'Detail Inputan Peserta Assessment')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Detail Inputan Peserta Assessment</h1>
            <p class="mt-1 text-sm text-gray-600">Lihat dan kelola semua inputan peserta untuk setiap assessment</p>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('admin.assessment-inputs.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Nama Peserta -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Peserta</label>
                            <input type="text" 
                                   name="nama" 
                                   id="nama" 
                                   value="{{ request('nama') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Instansi -->
                        <div>
                            <label for="instansi" class="block text-sm font-medium text-gray-700">Instansi</label>
                            <input type="text" 
                                   name="instansi" 
                                   id="instansi" 
                                   value="{{ request('instansi') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Assessment -->
                        <div>
                            <label for="assessment_id" class="block text-sm font-medium text-gray-700">Assessment</label>
                            <select name="assessment_id" 
                                    id="assessment_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Assessment</option>
                                @foreach($assessments as $assessment)
                                    <option value="{{ $assessment->id }}" 
                                            {{ request('assessment_id') == $assessment->id ? 'selected' : '' }}>
                                        {{ $assessment->nama }} ({{ $assessment->sesiPenilaian->nama }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jenis Input -->
                        <div>
                            <label for="input_type" class="block text-sm font-medium text-gray-700">Jenis Input</label>
                            <select name="input_type" 
                                    id="input_type"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Jenis</option>
                                <option value="studi_kasus" {{ request('input_type') == 'studi_kasus' ? 'selected' : '' }}>Studi Kasus</option>
                                <option value="in_tray" {{ request('input_type') == 'in_tray' ? 'selected' : '' }}>In-Tray Exercise</option>
                                <option value="roleplay" {{ request('input_type') == 'roleplay' ? 'selected' : '' }}>Role-Play</option>
                                <option value="fgd" {{ request('input_type') == 'fgd' ? 'selected' : '' }}>FGD</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex space-x-2">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            
                            <a href="{{ route('admin.assessment-inputs.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Reset
                            </a>
                        </div>

                        <!-- Export Options -->
                        <div class="flex items-center space-x-2">
                            <label for="delimiter" class="text-sm font-medium text-gray-700">Delimiter:</label>
                            <select name="delimiter" 
                                    id="delimiter"
                                    class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value=";">Semicolon (;)</option>
                                <option value=",">Comma (,)</option>
                            </select>
                            
                            <button type="button" 
                                    onclick="exportToCSV()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export CSV
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Hasil Pencarian
                    <span class="text-sm font-normal text-gray-500">
                        ({{ $inputs->total() }} data ditemukan)
                    </span>
                </h3>
            </div>

            @if($inputs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Peserta
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Assessment
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jawaban/Catatan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu Simpan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($inputs as $input)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $input->peserta_nama }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $input->peserta_instansi }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $input->peserta_jabatan }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $input->assessment_nama }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ ucfirst(str_replace('_', ' ', $input->assessment_jenis)) }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $input->sesi_nama }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs">
                                            @if(strlen($input->jawaban_catatan) > 100)
                                                <span class="cursor-pointer" 
                                                      onclick="toggleAnswer('{{ $input->id }}')"
                                                      id="answer-{{ $input->id }}-short">
                                                    {{ substr($input->jawaban_catatan, 0, 100) }}...
                                                    <span class="text-blue-600 text-xs">(lihat selengkapnya)</span>
                                                </span>
                                                <span class="hidden" id="answer-{{ $input->id }}-full">
                                                    {{ $input->jawaban_catatan }}
                                                    <span class="text-blue-600 text-xs cursor-pointer" 
                                                          onclick="toggleAnswer('{{ $input->id }}')">(sembunyikan)</span>
                                                </span>
                                            @else
                                                {{ $input->jawaban_catatan }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($input->status == 'selesai') bg-green-100 text-green-800
                                            @elseif($input->status == 'draft') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($input->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $input->waktu_simpan ? \Carbon\Carbon::parse($input->waktu_simpan)->format('d/m/Y H:i') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($inputs->previousPageUrl())
                            <a href="{{ $inputs->previousPageUrl() }}" 
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Sebelumnya
                            </a>
                        @endif
                        
                        @if($inputs->nextPageUrl())
                            <a href="{{ $inputs->nextPageUrl() }}" 
                               class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Selanjutnya
                            </a>
                        @endif
                    </div>
                    
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan
                                <span class="font-medium">{{ $inputs->firstItem() }}</span>
                                sampai
                                <span class="font-medium">{{ $inputs->lastItem() }}</span>
                                dari
                                <span class="font-medium">{{ $inputs->total() }}</span>
                                hasil
                            </p>
                        </div>
                        <div>
                            {{ $inputs->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                    <p class="mt-1 text-sm text-gray-500">Tidak ada inputan peserta yang sesuai dengan filter yang dipilih.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleAnswer(id) {
    const shortElement = document.getElementById(`answer-${id}-short`);
    const fullElement = document.getElementById(`answer-${id}-full`);
    
    if (shortElement.classList.contains('hidden')) {
        shortElement.classList.remove('hidden');
        fullElement.classList.add('hidden');
    } else {
        shortElement.classList.add('hidden');
        fullElement.classList.remove('hidden');
    }
}

function exportToCSV() {
    const delimiter = document.getElementById('delimiter').value;
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '{{ route("admin.assessment-inputs.export") }}';
    
    // Add current filter parameters
    const currentParams = new URLSearchParams(window.location.search);
    for (const [key, value] of currentParams) {
        if (key !== 'delimiter') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
    }
    
    // Add delimiter parameter
    const delimiterInput = document.createElement('input');
    delimiterInput.type = 'hidden';
    delimiterInput.name = 'delimiter';
    delimiterInput.value = delimiter;
    form.appendChild(delimiterInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endsection
