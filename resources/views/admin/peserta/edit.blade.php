@extends('admin.layouts.app')

@section('title', 'Edit Peserta')

@section('content')
@include('admin.partials.page-header', [
    'title' => 'Edit Peserta',
    'subtitle' => 'Edit informasi peserta assessment center',
    'actions' => '<a href="' . route('admin.peserta.show', $peserta->id) . '" class="admin-btn-secondary">Batal</a>',
])

@include('admin.partials.alerts')

        <!-- Edit Form -->
        <div class="admin-card p-6">
            <form method="POST" action="{{ route('admin.peserta.update', $peserta->id) }}">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="nama_lengkap" class="block text-sm font-medium text-primary mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nama_lengkap" 
                            name="nama_lengkap" 
                            value="{{ old('nama_lengkap', $peserta->nama_lengkap) }}"
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            placeholder="Nama Lengkap Peserta"
                            required
                        >
                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-primary mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $peserta->email) }}"
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            placeholder="email@example.com"
                            required
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-primary mb-2">
                            Tempat Lahir
                        </label>
                        <input 
                            type="text" 
                            id="tempat_lahir" 
                            name="tempat_lahir" 
                            value="{{ old('tempat_lahir', $peserta->tempat_lahir) }}"
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            placeholder="Jakarta"
                        >
                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-primary mb-2">
                            Tanggal Lahir
                        </label>
                        <input 
                            type="date" 
                            id="tanggal_lahir" 
                            name="tanggal_lahir" 
                            value="{{ old('tanggal_lahir', $peserta->tanggal_lahir) }}"
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        >
                        @error('tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-primary mb-2">
                            Jenis Kelamin
                        </label>
                        <select 
                            id="jenis_kelamin" 
                            name="jenis_kelamin" 
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        >
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ $peserta->jenis_kelamin === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $peserta->jenis_kelamin === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="nomor_telepon" class="block text-sm font-medium text-primary mb-2">
                            Nomor Telepon
                        </label>
                        <input 
                            type="text" 
                            id="nomor_telepon" 
                            name="nomor_telepon" 
                            value="{{ old('nomor_telepon', $peserta->nomor_telepon) }}"
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            placeholder="08123456789"
                        >
                        @error('nomor_telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instansi -->
                    <div>
                        <label for="instansi" class="block text-sm font-medium text-primary mb-2">
                            Instansi
                        </label>
                        <input 
                            type="text" 
                            id="instansi" 
                            name="instansi" 
                            value="{{ old('instansi', $peserta->instansi) }}"
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            placeholder="Nama Instansi"
                        >
                        @error('instansi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan_saat_ini" class="block text-sm font-medium text-primary mb-2">
                            Jabatan Saat Ini
                        </label>
                        <input 
                            type="text" 
                            id="jabatan_saat_ini" 
                            name="jabatan_saat_ini" 
                            value="{{ old('jabatan_saat_ini', $peserta->jabatan_saat_ini) }}"
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            placeholder="Staff / Manager / dll"
                        >
                        @error('jabatan_saat_ini')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grade -->
                    <div>
                        <label for="grade" class="block text-sm font-medium text-primary mb-2">
                            Grade
                        </label>
                        <input 
                            type="text" 
                            id="grade" 
                            name="grade" 
                            value="{{ old('grade', $peserta->grade) }}"
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            placeholder="E1 / E2 / E3 / dll"
                        >
                        @error('grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PIN -->
                    <div>
                        <label for="pin" class="block text-sm font-medium text-primary mb-2">
                            PIN <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="pin" 
                            name="pin" 
                            value="{{ old('pin', $peserta->pin) }}"
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 font-mono text-center"
                            placeholder="123456"
                            maxlength="10"
                            required
                        >
                        <p class="mt-1 text-xs text-tertiary">PIN untuk login peserta (maksimal 10 karakter)</p>
                        @error('pin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat Rumah -->
                    <div class="md:col-span-2">
                        <label for="alamat_rumah" class="block text-sm font-medium text-primary mb-2">
                            Alamat Rumah
                        </label>
                        <textarea 
                            id="alamat_rumah" 
                            name="alamat_rumah" 
                            rows="3"
                            class="w-full px-4 py-3 admin-input focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            placeholder="Alamat lengkap peserta..."
                        >{{ old('alamat_rumah', $peserta->alamat_rumah) }}</textarea>
                        @error('alamat_rumah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a 
                        href="{{ route('admin.peserta.show', $peserta->id) }}" 
                        class="admin-btn-secondary"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="admin-btn-primary"
                    >
                        Update Peserta
                    </button>
                </div>
            </form>
        </div>
@endsection
