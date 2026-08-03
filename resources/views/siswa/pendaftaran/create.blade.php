@extends('layouts.siswa')

@section('title', 'Form Pendaftaran')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 mb-8 text-white fade-in-up">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                <i class="fas fa-file-alt text-3xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold">Form Pendaftaran Siswa Baru</h1>
                <p class="text-indigo-100 mt-1">Lengkapi data diri Anda dengan benar</p>
            </div>
        </div>
    </div>


    <!-- Form -->
    <form action="{{ route('siswa.pendaftaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Pilih Jurusan -->
        <div class="bg-white rounded-2xl shadow-xl p-8 fade-in-up" style="animation-delay: 0.2s">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-graduation-cap text-indigo-600 mr-3"></i>
                Pilih Jurusan
            </h3>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($jurusans as $jurusan)
                <label class="relative">
                    <input type="radio" name="jurusan_id" value="{{ $jurusan->id }}" 
                           class="peer sr-only" required>
                    <div class="p-6 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:shadow-lg hover:border-indigo-300">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">
                                {{ $jurusan->kode_jurusan }}
                            </span>
                            <i class="fas fa-check-circle text-indigo-600 text-xl opacity-0 peer-checked:opacity-100"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-2">{{ $jurusan->nama_jurusan }}</h4>
                        <p class="text-sm text-gray-600">{{ Str::limit($jurusan->deskripsi, 80) }}</p>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500 font-semibold">
                                <i class="fas fa-users mr-1"></i>Sisa Kuota: 
                                <span class="text-indigo-600">
                                    {{ max(0, $jurusan->kuota - $jurusan->siswa_diterima_count) }}
                                </span> 
                                / {{ $jurusan->kuota }}
                            </p>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            @error('jurusan_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

<!-- Data Diri -->
<div class="bg-white rounded-2xl shadow-xl p-8 fade-in-up" style="animation-delay: 0.3s">
    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-user text-indigo-600 mr-3"></i>
        Data Diri
    </h3>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Nama Lengkap -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                   placeholder="Masukkan nama lengkap">
            @error('nama_lengkap')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- NIK -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                   placeholder="16 digit NIK">
            @error('nik')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- NISN -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                NISN (Nomor Induk Siswa Nasional) <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nisn" value="{{ old('nisn') }}" required maxlength="10"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                   placeholder="10 digit NISN">
            @error('nisn')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jenis Kelamin -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Jenis Kelamin <span class="text-red-500">*</span>
            </label>
            <div class="flex space-x-4 pt-2">
                <label class="flex items-center">
                    <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required
                           class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-gray-700">Laki-laki</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required
                           class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-gray-700">Perempuan</span>
                </label>
            </div>
            @error('jenis_kelamin')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Agama -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Agama <span class="text-red-500">*</span>
            </label>
            <input type="text" name="agama" value="{{ old('agama') }}" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                   placeholder="Contoh: Islam, Kristen, dll.">
            @error('agama')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tempat Lahir -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tempat Lahir <span class="text-red-500">*</span>
            </label>
            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                   placeholder="Tempat lahir">
            @error('tempat_lahir')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Lahir -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal Lahir <span class="text-red-500">*</span>
            </label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            @error('tanggal_lahir')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Asal Sekolah -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Asal Sekolah <span class="text-red-500">*</span>
            </label>
            <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                   placeholder="SD/SMP Terakhir">
            @error('asal_sekolah')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tempat Tinggal -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tempat Tinggal <span class="text-red-500">*</span>
            </label>
            <select name="tempat_tinggal" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                <option value="" disabled {{ old('tempat_tinggal') ? '' : 'selected' }}>Pilih Tempat Tinggal</option>
                @foreach(['Bersama Orang Tua', 'Wali', 'Kos', 'Asrama', 'Panti Asuhan'] as $tt)
                    <option value="{{ $tt }}" {{ old('tempat_tinggal') == $tt ? 'selected' : '' }}>{{ $tt }}</option>
                @endforeach
            </select>
            @error('tempat_tinggal')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kewarganegaraan -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Kewarganegaraan <span class="text-red-500">*</span>
            </label>
            <div class="flex space-x-6 pt-1">
                <label class="flex items-center">
                    <input type="radio" name="kewarganegaraan" value="Indonesia" {{ old('kewarganegaraan', 'Indonesia') == 'Indonesia' ? 'checked' : '' }} required
                           class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-gray-700">Indonesia (WNI)</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="kewarganegaraan" value="Asing" {{ old('kewarganegaraan') == 'Asing' ? 'checked' : '' }} required
                           class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-gray-700">Asing (WNA)</span>
                </label>
            </div>
            @error('kewarganegaraan')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Alamat -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Alamat Lengkap <span class="text-red-500">*</span>
            </label>
            <textarea name="alamat" rows="3" required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                      placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat') }}</textarea>
            @error('alamat')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

        <!-- Data Orang Tua -->
<div class="bg-white rounded-2xl shadow-xl p-8 fade-in-up mb-8" style="animation-delay: 0.5s">
    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-users text-indigo-600 mr-3"></i>
        Data Orang Tua
    </h3>

    <!-- Data Ayah Kandung -->
    <div class="mb-8 border-b border-gray-200 pb-6">
        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
            <i class="fas fa-user-tie text-indigo-500 mr-2 text-sm"></i>
            Data Ayah Kandung
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Nama Ayah Kandung -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Ayah Kandung <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_ayah_kandung" value="{{ old('nama_ayah_kandung') }}" required
                       placeholder="Masukkan nama lengkap ayah"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                @error('nama_ayah_kandung')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tempat, Tanggal Lahir Ayah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tempat, Tanggal Lahir <span class="text-red-500">*</span>
                </label>
                <input type="text" name="ttl_ayah" value="{{ old('ttl_ayah') }}" required
                       placeholder="Contoh: Jakarta, 15 Januari 1975"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                @error('ttl_ayah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pendidikan Terakhir Ayah (pta_ayah) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pendidikan Terakhir <span class="text-red-500">*</span>
                </label>
                <select name="pta_ayah" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                    <option value="" disabled {{ old('pta_ayah') ? '' : 'selected' }}>Pilih Pendidikan</option>
                    @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'] as $pta)
                        <option value="{{ $pta }}" {{ old('pta_ayah') == $pta ? 'selected' : '' }}>{{ $pta }}</option>
                    @endforeach
                </select>
                @error('pta_ayah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pekerjaan Ayah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pekerjaan <span class="text-red-500">*</span>
                </label>
                <select name="pekerjaan_ayah" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                    <option value="" disabled {{ old('pekerjaan_ayah') ? '' : 'selected' }}>Pilih Pekerjaan</option>
                    @foreach(['Tidak Bekerja','Nelayan','Petani','Peternak','PNS/TNI/POLRI','Karyawan Swasta','Pedagang Kecil','Pedagang Besar','Wiraswasta','Buruh','Pensiunan','PPPK'] as $pekerjaan)
                        <option value="{{ $pekerjaan }}" {{ old('pekerjaan_ayah') == $pekerjaan ? 'selected' : '' }}>{{ $pekerjaan }}</option>
                    @endforeach
                </select>
                @error('pekerjaan_ayah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penghasilan Ayah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Penghasilan Bulanan <span class="text-red-500">*</span>
                </label>
                <select name="penghasilan_ayah" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                    <option value="" disabled {{ old('penghasilan_ayah') ? '' : 'selected' }}>Pilih Rentang Penghasilan</option>
                    @foreach(['Rp0 - Rp2.000.000', 'Rp2.000.000 - Rp5.000.000', 'Rp5.000.000 - Rp20.000.000', '> Rp20.000.000'] as $penghasilan)
                        <option value="{{ $penghasilan }}" {{ old('penghasilan_ayah') == $penghasilan ? 'selected' : '' }}>{{ $penghasilan }}</option>
                    @endforeach
                </select>
                @error('penghasilan_ayah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- No HP Ayah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    No. HP / WhatsApp <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="no_hp_ayah" value="{{ old('no_hp_ayah') }}" required
                       placeholder="Contoh: 081234567890"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                @error('no_hp_ayah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Data Ibu Kandung -->
    <div>
        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
            <i class="fas fa-user-nurse text-indigo-500 mr-2 text-sm"></i>
            Data Ibu Kandung
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Nama Ibu Kandung -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Ibu Kandung <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_ibu_kandung" value="{{ old('nama_ibu_kandung') }}" required
                       placeholder="Masukkan nama lengkap ibu"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                @error('nama_ibu_kandung')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tempat, Tanggal Lahir Ibu -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tempat, Tanggal Lahir <span class="text-red-500">*</span>
                </label>
                <input type="text" name="ttl_ibu" value="{{ old('ttl_ibu') }}" required
                       placeholder="Contoh: Bandung, 20 Agustus 1978"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                @error('ttl_ibu')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pendidikan Terakhir Ibu (pta_ibu) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pendidikan Terakhir <span class="text-red-500">*</span>
                </label>
                <select name="pta_ibu" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                    <option value="" disabled {{ old('pta_ibu') ? '' : 'selected' }}>Pilih Pendidikan</option>
                    @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'] as $pta)
                        <option value="{{ $pta }}" {{ old('pta_ibu') == $pta ? 'selected' : '' }}>{{ $pta }}</option>
                    @endforeach
                </select>
                @error('pta_ibu')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pekerjaan Ibu -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pekerjaan <span class="text-red-500">*</span>
                </label>
                <select name="pekerjaan_ibu" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                    <option value="" disabled {{ old('pekerjaan_ibu') ? '' : 'selected' }}>Pilih Pekerjaan</option>
                    @foreach(['Tidak Bekerja','Nelayan','Petani','Peternak','PNS/TNI/POLRI','Karyawan Swasta','Pedagang Kecil','Pedagang Besar','Wiraswasta','Buruh','Pensiunan','PPPK'] as $pekerjaan)
                        <option value="{{ $pekerjaan }}" {{ old('pekerjaan_ibu') == $pekerjaan ? 'selected' : '' }}>{{ $pekerjaan }}</option>
                    @endforeach
                </select>
                @error('pekerjaan_ibu')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penghasilan Ibu -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Penghasilan Bulanan <span class="text-red-500">*</span>
                </label>
                <select name="penghasilan_ibu" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                    <option value="" disabled {{ old('penghasilan_ibu') ? '' : 'selected' }}>Pilih Rentang Penghasilan</option>
                    @foreach(['Rp0 - Rp2.000.000', 'Rp2.000.000 - Rp5.000.000', 'Rp5.000.000 - Rp20.000.000', '> Rp20.000.000'] as $penghasilan)
                        <option value="{{ $penghasilan }}" {{ old('penghasilan_ibu') == $penghasilan ? 'selected' : '' }}>{{ $penghasilan }}</option>
                    @endforeach
                </select>
                @error('penghasilan_ibu')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- No HP Ibu -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    No. HP / WhatsApp <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="no_hp_ibu" value="{{ old('no_hp_ibu') }}" required
                       placeholder="Contoh: 081234567890"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors">
                @error('no_hp_ibu')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

        <!-- Upload Foto -->
            <!-- Section Upload Foto Diri & Dokumen Persyaratan -->
<div class="bg-white rounded-2xl shadow-xl p-8 fade-in-up mb-8" style="animation-delay: 0.4s">
    
    <!-- 1. Upload Foto Diri (3x4) dengan Live Preview -->
    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-camera text-indigo-600 mr-3"></i>
        Upload Foto Diri
    </h3>

    <div class="grid md:grid-cols-2 gap-6 items-center pb-8 border-b border-gray-200 mb-8">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Foto Diri (3x4) <span class="text-red-500">*</span>
            </label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 transition-colors bg-gray-50">
                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                <input type="file" name="foto" accept="image/png,image/jpeg,image/jpg" required
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                       onchange="previewImage(event)">
                <p class="text-xs text-gray-500 mt-2">Format: PNG, JPG, JPEG (Max. 2MB)</p>
            </div>
            @error('foto')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-center">
            <div class="text-center">
                <img id="preview" src="https://via.placeholder.com/200x250?text=Preview+Foto" 
                     alt="Preview Foto Diri" 
                     class="w-48 h-60 object-cover rounded-lg shadow-lg border border-gray-200">
                <p class="text-xs text-gray-500 mt-2">Preview Foto Anda</p>
            </div>
        </div>
    </div>

    <!-- 2. Upload Dokumen Persyaratan (Scan PDF / Image) -->
    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-folder-open text-indigo-600 mr-3"></i>
        Upload Dokumen Persyaratan
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Scan Kartu Keluarga -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Scan Kartu Keluarga (KK) <span class="text-red-500">*</span>
            </label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-5 text-center hover:border-indigo-500 transition-colors bg-gray-50">
                <i class="fas fa-file-invoice text-3xl text-gray-400 mb-2"></i>
                <input type="file" name="scan_kk" accept=".pdf,.png,.jpg,.jpeg" required
                       class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-2">PDF, PNG, JPG (Max. 2MB)</p>
            </div>
            @error('scan_kk')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Scan Akta Kelahiran -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Scan Akta Kelahiran <span class="text-red-500">*</span>
            </label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-5 text-center hover:border-indigo-500 transition-colors bg-gray-50">
                <i class="fas fa-certificate text-3xl text-gray-400 mb-2"></i>
                <input type="file" name="scan_akta_kelahiran" accept=".pdf,.png,.jpg,.jpeg" required
                       class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-2">PDF, PNG, JPG (Max. 2MB)</p>
            </div>
            @error('scan_akta_kelahiran')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Scan Ijazah Terakhir -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Scan Ijazah Terakhir (SD/SMP) <span class="text-red-500">*</span>
            </label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-5 text-center hover:border-indigo-500 transition-colors bg-gray-50">
                <i class="fas fa-graduation-cap text-3xl text-gray-400 mb-2"></i>
                <input type="file" name="scan_ijazah_terakhir" accept=".pdf,.png,.jpg,.jpeg" required
                       class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-2">PDF, PNG, JPG (Max. 2MB)</p>
            </div>
            @error('scan_ijazah_terakhir')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Scan KTP Ayah -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Scan KTP Ayah <span class="text-red-500">*</span>
            </label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-5 text-center hover:border-indigo-500 transition-colors bg-gray-50">
                <i class="fas fa-address-card text-3xl text-gray-400 mb-2"></i>
                <input type="file" name="scan_ktp_ayah" accept=".pdf,.png,.jpg,.jpeg" required
                       class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-2">PDF, PNG, JPG (Max. 2MB)</p>
            </div>
            @error('scan_ktp_ayah')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Scan KTP Ibu -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Scan KTP Ibu <span class="text-red-500">*</span>
            </label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-5 text-center hover:border-indigo-500 transition-colors bg-gray-50">
                <i class="fas fa-id-card text-3xl text-gray-400 mb-2"></i>
                <input type="file" name="scan_ktp_ibu" accept=".pdf,.png,.jpg,.jpeg" required
                       class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-2">PDF, PNG, JPG (Max. 2MB)</p>
            </div>
            @error('scan_ktp_ibu')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

    </div>
</div>

<!-- Script Preview Foto (JS) -->
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('preview');
            output.src = reader.result;
        };
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>


        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4 fade-in-up" style="animation-delay: 0.5s">
            <a href="{{ route('siswa.dashboard') }}" 
               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl">
                <i class="fas fa-paper-plane mr-2"></i>
                Kirim Pendaftaran
            </button>
        </div>
    </form>

</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection