@extends('layouts.admin')

@section('title', 'Detail Siswa')
@section('subtitle', 'Detail lengkap data pendaftaran calon siswa')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Ringkasan, Status & Verifikasi -->
    <div class="space-y-6">
        <!-- Card Profile & Status -->
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            @if($siswa->foto)
                <img src="{{ Storage::url($siswa->foto) }}" 
                     alt="Foto {{ $siswa->nama_lengkap }}" 
                     class="w-36 h-36 rounded-full mx-auto object-cover border-4 border-blue-50 shadow-sm mb-4">
            @else
                <div class="w-36 h-36 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4 text-blue-500 text-5xl">
                    <i class="fas fa-user"></i>
                </div>
            @endif
            
            <h2 class="text-xl font-bold text-gray-800">{{ $siswa->nama_lengkap }}</h2>
            <p class="text-gray-500 text-sm mb-1">No. Reg: <span class="font-semibold text-gray-700">{{ $siswa->no_pendaftaran }}</span></p>
            <p class="text-blue-600 font-medium text-sm mb-4">{{ $siswa->jurusan->nama_jurusan ?? '-' }}</p>
            
            <div class="inline-block px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider
                {{ $siswa->status_pendaftaran === 'diverifikasi' ? 'bg-blue-100 text-blue-700' : 
                  ($siswa->status_pendaftaran === 'diterima' ? 'bg-green-100 text-green-700' : 
                  ($siswa->status_pendaftaran === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')) }}">
                {{ $siswa->status_pendaftaran }}
            </div>
        </div>

        <!-- Form Verifikasi Admin -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2 flex items-center space-x-2">
                <i class="fas fa-user-check text-blue-600"></i>
                <span>Verifikasi Pendaftaran</span>
            </h3>
            <form action="{{ route('admin.siswa.verifikasi', $siswa->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                    <select name="status_pendaftaran" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="pending" {{ $siswa->status_pendaftaran == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="diverifikasi" {{ $siswa->status_pendaftaran == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                        <option value="diterima" {{ $siswa->status_pendaftaran == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ $siswa->status_pendaftaran == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                    <textarea name="catatan_admin" rows="3" 
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm"
                              placeholder="Berikan catatan atau alasan jika ditolak...">{{ $siswa->catatan_admin }}</textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Status</span>
                </button>
            </form>
        </div>
        
        <!-- Aksi Cepat / Hapus -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2 text-sm text-gray-500 uppercase">Aksi Cepat</h3>
            <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh data siswa ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full flex items-center justify-center space-x-2 text-red-600 hover:bg-red-50 p-2.5 rounded-lg border border-red-200 transition-colors text-sm font-medium">
                    <i class="fas fa-trash-alt"></i>
                    <span>Hapus Data Siswa</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Detail Informasi Lengkap -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Data Diri Siswa -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-100 flex items-center space-x-2">
                <i class="fas fa-id-card text-blue-600"></i>
                <h3 class="font-bold text-gray-800">Data Diri Siswa</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Nama Lengkap</p>
                    <p class="font-medium text-gray-800">{{ $siswa->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">NIK</p>
                    <p class="font-medium text-gray-800">{{ $siswa->nik }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">NISN</p>
                    <p class="font-medium text-gray-800">{{ $siswa->nisn }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Jenis Kelamin</p>
                    <p class="font-medium text-gray-800">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Tempat, Tanggal Lahir</p>
                    <p class="font-medium text-gray-800">{{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Agama</p>
                    <p class="font-medium text-gray-800">{{ $siswa->agama }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Kewarganegaraan</p>
                    <p class="font-medium text-gray-800">{{ $siswa->kewarganegaraan }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Tempat Tinggal</p>
                    <p class="font-medium text-gray-800">{{ $siswa->tempat_tinggal }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Asal Sekolah</p>
                    <p class="font-medium text-gray-800">{{ $siswa->asal_sekolah }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs text-gray-400 font-semibold uppercase">Alamat Lengkap</p>
                    <p class="font-medium text-gray-800 leading-relaxed">{{ $siswa->alamat }}</p>
                </div>
            </div>
        </div>

        <!-- Data Orang Tua (Ayah & Ibu) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-100 flex items-center space-x-2">
                <i class="fas fa-users text-blue-600"></i>
                <h3 class="font-bold text-gray-800">Data Orang Tua</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <!-- Data Ayah -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 space-y-3">
                    <h4 class="font-bold text-blue-700 border-b pb-2 flex items-center space-x-2">
                        <i class="fas fa-male"></i>
                        <span>Data Ayah Kandung</span>
                    </h4>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Nama Ayah</p>
                        <p class="font-medium text-gray-800">{{ $siswa->nama_ayah_kandung }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Tempat, Tanggal Lahir</p>
                        <p class="font-medium text-gray-800">{{ $siswa->ttl_ayah }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Pendidikan Terakhir</p>
                        <p class="font-medium text-gray-800">{{ $siswa->pta_ayah }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Pekerjaan</p>
                        <p class="font-medium text-gray-800">{{ $siswa->pekerjaan_ayah }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Penghasilan Bulanan</p>
                        <p class="font-medium text-gray-800">{{ $siswa->penghasilan_ayah }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">No. HP / WA</p>
                        <p class="font-medium text-gray-800">{{ $siswa->no_hp_ayah }}</p>
                    </div>
                </div>

                <!-- Data Ibu -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 space-y-3">
                    <h4 class="font-bold text-pink-600 border-b pb-2 flex items-center space-x-2">
                        <i class="fas fa-female"></i>
                        <span>Data Ibu Kandung</span>
                    </h4>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Nama Ibu</p>
                        <p class="font-medium text-gray-800">{{ $siswa->nama_ibu_kandung }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Tempat, Tanggal Lahir</p>
                        <p class="font-medium text-gray-800">{{ $siswa->ttl_ibu }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Pendidikan Terakhir</p>
                        <p class="font-medium text-gray-800">{{ $siswa->pta_ibu }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Pekerjaan</p>
                        <p class="font-medium text-gray-800">{{ $siswa->pekerjaan_ibu }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Penghasilan Bulanan</p>
                        <p class="font-medium text-gray-800">{{ $siswa->penghasilan_ibu }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">No. HP / WA</p>
                        <p class="font-medium text-gray-800">{{ $siswa->no_hp_ibu }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Berkas & Dokumen Persyaratan -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-100 flex items-center space-x-2">
                <i class="fas fa-file-alt text-blue-600"></i>
                <h3 class="font-bold text-gray-800">Berkas & Dokumen Persyaratan</h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <!-- Scan KK -->
                <div class="border rounded-lg p-3 text-center space-y-2 bg-gray-50 hover:bg-white transition-colors">
                    <p class="font-semibold text-gray-700 text-xs uppercase">Scan Kartu Keluarga</p>
                    @if($siswa->scan_kk)
                        <a href="{{ Storage::url($siswa->scan_kk) }}" target="_blank" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Lihat Berkas</span>
                        </a>
                    @else
                        <span class="text-xs text-red-500 block">Belum ada</span>
                    @endif
                </div>

                <!-- Scan Akta -->
                <div class="border rounded-lg p-3 text-center space-y-2 bg-gray-50 hover:bg-white transition-colors">
                    <p class="font-semibold text-gray-700 text-xs uppercase">Scan Akta Kelahiran</p>
                    @if($siswa->scan_akta_kelahiran)
                        <a href="{{ Storage::url($siswa->scan_akta_kelahiran) }}" target="_blank" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Lihat Berkas</span>
                        </a>
                    @else
                        <span class="text-xs text-red-500 block">Belum ada</span>
                    @endif
                </div>

                <!-- Scan Ijazah -->
                <div class="border rounded-lg p-3 text-center space-y-2 bg-gray-50 hover:bg-white transition-colors">
                    <p class="font-semibold text-gray-700 text-xs uppercase">Scan Ijazah Terakhir</p>
                    @if($siswa->scan_ijazah_terakhir)
                        <a href="{{ Storage::url($siswa->scan_ijazah_terakhir) }}" target="_blank" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Lihat Berkas</span>
                        </a>
                    @else
                        <span class="text-xs text-red-500 block">Belum ada</span>
                    @endif
                </div>

                <!-- Scan KTP Ayah -->
                <div class="border rounded-lg p-3 text-center space-y-2 bg-gray-50 hover:bg-white transition-colors">
                    <p class="font-semibold text-gray-700 text-xs uppercase">Scan KTP Ayah</p>
                    @if($siswa->scan_ktp_ayah)
                        <a href="{{ Storage::url($siswa->scan_ktp_ayah) }}" target="_blank" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Lihat Berkas</span>
                        </a>
                    @else
                        <span class="text-xs text-red-500 block">Belum ada</span>
                    @endif
                </div>

                <!-- Scan KTP Ibu -->
                <div class="border rounded-lg p-3 text-center space-y-2 bg-gray-50 hover:bg-white transition-colors">
                    <p class="font-semibold text-gray-700 text-xs uppercase">Scan KTP Ibu</p>
                    @if($siswa->scan_ktp_ibu)
                        <a href="{{ Storage::url($siswa->scan_ktp_ibu) }}" target="_blank" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Lihat Berkas</span>
                        </a>
                    @else
                        <span class="text-xs text-red-500 block">Belum ada</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center space-x-2">
                    <i class="fas fa-receipt text-blue-600"></i>
                    <span>Riwayat Pembayaran</span>
                </h3>
            </div>
            <div class="p-6">
                @if($siswa->pembayarans && $siswa->pembayarans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-gray-500 border-b bg-gray-50 text-xs uppercase">
                                <tr>
                                    <th class="py-3 px-2">No Pembayaran</th>
                                    <th class="py-3 px-2">Tanggal</th>
                                    <th class="py-3 px-2">Jumlah</th>
                                    <th class="py-3 px-2">Status</th>
                                    <th class="py-3 px-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($siswa->pembayarans as $pembayaran)
                                <tr>
                                    <td class="py-3 px-2 font-medium">{{ $pembayaran->no_pembayaran }}</td>
                                    <td class="py-3 px-2">{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y') }}</td>
                                    <td class="py-3 px-2">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                    <td class="py-3 px-2">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                            {{ $pembayaran->status == 'terverifikasi' ? 'bg-green-100 text-green-700' : 
                                               ($pembayaran->status == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ ucfirst($pembayaran->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2 text-right">
                                        <a href="{{ route('admin.pembayaran.show', $pembayaran->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Lihat</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-4 text-sm">Belum ada riwayat pembayaran</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection