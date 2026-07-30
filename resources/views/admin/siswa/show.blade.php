@extends('layouts.admin')

@section('title', 'Detail siswa')
@section('subtitle', 'Detail data pendaftaran calon siswa')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Info Utama & Status -->
    <div class="space-y-6">
        <!-- Foto & Status -->
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            @if($siswa->foto)
                <img src="{{ Storage::url($siswa->foto) }}" 
                     alt="Foto {{ $siswa->nama_lengkap }}" 
                     class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-blue-50 mb-4">
            @else
                <div class="w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4 text-blue-500 text-4xl">
                    <i class="fas fa-user"></i>
                </div>
            @endif
            
            <h2 class="text-xl font-bold text-gray-800">{{ $siswa->nama_lengkap }}</h2>
            <p class="text-gray-500 text-sm mb-4">{{ $siswa->no_pendaftaran }}</p>
            
            <div class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold 
                {{ $siswa->status_pendaftaran === 'diverifikasi' ? 'bg-blue-100 text-blue-700' : 
                  ($siswa->status_pendaftaran === 'diterima' ? 'bg-green-100 text-green-700' : 
                  ($siswa->status_pendaftaran === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')) }}">
                {{ ucfirst($siswa->status_pendaftaran) }}
            </div>
        </div>

        <!-- Form Verifikasi -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Verifikasi Pendaftaran</h3>
            <form action="{{ route('admin.siswa.verifikasi', $siswa->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                    <select name="status_pendaftaran" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="pending" {{ $siswa->status_pendaftaran == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="diverifikasi" {{ $siswa->status_pendaftaran == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                        <option value="ditolak" {{ $siswa->status_pendaftaran == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                    <textarea name="catatan_admin" rows="3" 
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Berikan alasan jika ditolak...">{{ $siswa->catatan_admin }}</textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Simpan Perubahan
                </button>
            </form>
        </div>
        
        <!-- Quick Actions -->
         <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Aksi Cepat</h3>
             <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full flex items-center justify-center space-x-2 text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors">
                    <i class="fas fa-trash-alt"></i>
                    <span>Hapus Data siswa</span>
                </button>
            </form>
         </div>
    </div>

    <!-- Kolom Kanan: Detail Data -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Data Pribadi -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Data Pribadi</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                    <p class="font-medium text-gray-800">{{ $siswa->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jenis Kelamin</p>
                    <p class="font-medium text-gray-800">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tempat, Tanggal Lahir</p>
                    <p class="font-medium text-gray-800">{{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nomor HP</p>
                    <p class="font-medium text-gray-800">{{ $siswa->no_hp }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Alamat</p>
                    <p class="font-medium text-gray-800">{{ $siswa->alamat }}</p>
                </div>
            </div>
        </div>

        <!-- Data Akademik -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
             <div class="p-4 bg-gray-50 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Data Akademik</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Program</p>
                    <p class="font-medium text-gray-800">{{ $siswa->asal_sekolah }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Program Pilihan</p>
                    <p class="font-medium text-blue-600">{{ $siswa->jurusan->nama_jurusan ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
             <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Riwayat Pembayaran</h3>
            </div>
            <div class="p-6">
                @if($siswa->pembayarans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-gray-500 border-b">
                                <tr>
                                    <th class="pb-3">No Pembayaran</th>
                                    <th class="pb-3">Tanggal</th>
                                    <th class="pb-3">Jumlah</th>
                                    <th class="pb-3">Status</th>
                                    <th class="pb-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($siswa->pembayarans as $pembayaran)
                                <tr>
                                    <td class="py-3 font-medium">{{ $pembayaran->no_pembayaran }}</td>
                                    <td class="py-3">{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y') }}</td>
                                    <td class="py-3">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $pembayaran->status == 'terverifikasi' ? 'bg-green-100 text-green-700' : 
                                               ($pembayaran->status == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ ucfirst($pembayaran->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('admin.pembayaran.show', $pembayaran->id) }}" class="text-blue-600 hover:text-blue-800">Lihat</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-4">Belum ada riwayat pembayaran</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
