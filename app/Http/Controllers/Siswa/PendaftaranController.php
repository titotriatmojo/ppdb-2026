<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    // Form pendaftaran
    public function create()
    {
        // Cek apakah sudah pernah daftar
        if (auth()->user()->siswa) {
            return redirect()->route('siswa.pendaftaran.status')
                             ->with('info', 'Anda sudah melakukan pendaftaran');
        }

        // Ambil jurusan dengan menghitung jumlah siswa yang sudah DITERIMA
        // HANYA JURUSAN AKTIF
        $jurusans = Jurusan::where('status', 'active')
                           ->withCount('siswaDiterima')
                           ->get();
                           
        return view('siswa.pendaftaran.create', compact('jurusans'));
    }

    // Simpan pendaftaran
    public function store(Request $request)
    {
        // Cek apakah sudah pernah daftar
        if (auth()->user()->siswa) {
            return redirect()->route('siswa.pendaftaran.status')
                             ->with('error', 'Anda sudah melakukan pendaftaran');
        }

        // Validasi input
        $request->validate([
            'jurusan_id'          => 'required|exists:jurusans,id',
            'nama_lengkap'        => 'required|string|max:255',
            'nik'                 => 'required|string|max:16',
            'nisn'                => 'required|string|max:10',
            'jenis_kelamin'       => 'required|in:L,P',
            'agama'               => 'required|string|max:50',
            'tempat_lahir'        => 'required|string|max:255',
            'tanggal_lahir'       => 'required|date',
            'asal_sekolah'        => 'required|string|max:255',
            'tempat_tinggal'      => 'required|in:Bersama Orang Tua,Wali,Kos,Asrama,Panti Asuhan',
            'kewarganegaraan'     => 'required|in:Indonesia,Asing',
            'alamat'              => 'required|string',

            // Data Ayah
            'nama_ayah_kandung'   => 'required|string|max:255',
            'ttl_ayah'            => 'required|string|max:255',
            'pta_ayah'            => 'required|in:SD,SMP,SMA,D3,S1,S2,S3',
            'pekerjaan_ayah'      => 'required|in:Tidak Bekerja,Nelayan,Petani,Peternak,PNS/TNI/POLRI,Karyawan Swasta,Pedagang Kecil,Pedagang Besar,Wiraswasta,Buruh,Pensiunan,PPPK',
            'penghasilan_ayah'    => 'required|in:Rp0 - Rp2.000.000,Rp2.000.000 - Rp5.000.000,Rp5.000.000 - Rp20.000.000,> Rp20.000.000',
            'no_hp_ayah'          => 'required|string|max:20',

            // Data Ibu
            'nama_ibu_kandung'    => 'required|string|max:255',
            'ttl_ibu'             => 'required|string|max:255',
            'pta_ibu'             => 'required|in:SD,SMP,SMA,D3,S1,S2,S3',
            'pekerjaan_ibu'       => 'required|in:Tidak Bekerja,Nelayan,Petani,Peternak,PNS/TNI/POLRI,Karyawan Swasta,Pedagang Kecil,Pedagang Besar,Wiraswasta,Buruh,Pensiunan,PPPK',
            'penghasilan_ibu'     => 'required|in:Rp0 - Rp2.000.000,Rp2.000.000 - Rp5.000.000,Rp5.000.000 - Rp20.000.000,> Rp20.000.000',
            'no_hp_ibu'           => 'required|string|max:20',

            // Upload Foto & Dokumen
            'foto'                => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'scan_kk'             => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            'scan_akta_kelahiran' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            'scan_ijazah_terakhir'=> 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            'scan_ktp_ayah'       => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            'scan_ktp_ibu'        => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'foto.max'            => 'Ukuran foto maksimal 2MB. Silakan kompres foto Anda terlebih dahulu.',
            'foto.uploaded'       => 'Gagal mengupload foto. Ukuran file mungkin terlalu besar melebihi batas server.',
            'foto.mimes'          => 'Format foto harus berupa: jpeg, png, jpg, atau webp.',
            'foto.image'          => 'File yang diupload harus berupa gambar.',
            '*.max'               => 'Ukuran berkas dokumen tidak boleh melebihi 2MB.',
            '*.mimes'             => 'Format dokumen harus berupa PDF, JPG, JPEG, atau PNG.',
        ]);

        // ===== LOGIC KUOTA DIMULAI DI SINI =====
        $jurusan = Jurusan::withCount('siswaDiterima')->findOrFail($request->jurusan_id);
        
        if ($jurusan->status !== 'active') {
            return redirect()->back()
                             ->with('error', 'Maaf, jurusan ini sedang tidak menerima pendaftaran (Non-Aktif).')
                             ->withInput();
        }

        if ($jurusan->siswa_diterima_count >= $jurusan->kuota) {
            return redirect()->back()
                             ->with('error', 'Maaf, kuota untuk jurusan ' . $jurusan->nama_jurusan . ' sudah penuh!')
                             ->withInput();
        }
        // ===== LOGIC KUOTA SELESAI =====

        // Array penampung path file untuk rollback jika database gagal
        $uploadedPaths = [];

        try {
            // Generate nomor pendaftaran
            $no_pendaftaran = 'PMB' . date('Y') . str_pad(siswa::count() + 1, 4, '0', STR_PAD_LEFT);

            // Simpan foto
            $fotoPath = $request->file('foto')->store('foto-siswa', 'public');
            $uploadedPaths[] = $fotoPath;

            // Simpan dokumen-dokumen
            $scanKkPath     = $request->file('scan_kk')->store('dokumen/kk', 'public');
            $uploadedPaths[] = $scanKkPath;

            $scanAktaPath   = $request->file('scan_akta_kelahiran')->store('dokumen/akta', 'public');
            $uploadedPaths[] = $scanAktaPath;

            $scanIjazahPath = $request->file('scan_ijazah_terakhir')->store('dokumen/ijazah', 'public');
            $uploadedPaths[] = $scanIjazahPath;

            $scanKtpAyahPath = $request->file('scan_ktp_ayah')->store('dokumen/ktp-ayah', 'public');
            $uploadedPaths[] = $scanKtpAyahPath;

            $scanKtpIbuPath  = $request->file('scan_ktp_ibu')->store('dokumen/ktp-ibu', 'public');
            $uploadedPaths[] = $scanKtpIbuPath;

            // Simpan ke database
            siswa::create([
                'user_id'              => auth()->id(),
                'jurusan_id'           => $request->jurusan_id,
                'no_pendaftaran'       => $no_pendaftaran,
                'nama_lengkap'         => $request->nama_lengkap,
                'nik'                  => $request->nik,
                'nisn'                 => $request->nisn,
                'jenis_kelamin'        => $request->jenis_kelamin,
                'agama'                => $request->agama,
                'tempat_lahir'         => $request->tempat_lahir,
                'tanggal_lahir'        => $request->tanggal_lahir,
                'asal_sekolah'         => $request->asal_sekolah,
                'tempat_tinggal'       => $request->tempat_tinggal,
                'kewarganegaraan'      => $request->kewarganegaraan,
                'alamat'               => $request->alamat,

                // Data Ayah
                'nama_ayah_kandung'    => $request->nama_ayah_kandung,
                'ttl_ayah'             => $request->ttl_ayah,
                'pta_ayah'             => $request->pta_ayah,
                'pekerjaan_ayah'       => $request->pekerjaan_ayah,
                'penghasilan_ayah'     => $request->penghasilan_ayah,
                'no_hp_ayah'           => $request->no_hp_ayah,

                // Data Ibu
                'nama_ibu_kandung'     => $request->nama_ibu_kandung,
                'ttl_ibu'              => $request->ttl_ibu,
                'pta_ibu'              => $request->pta_ibu,
                'pekerjaan_ibu'        => $request->pekerjaan_ibu,
                'penghasilan_ibu'      => $request->penghasilan_ibu,
                'no_hp_ibu'            => $request->no_hp_ibu,

                // File Upload
                'foto'                 => $fotoPath,
                'scan_kk'              => $scanKkPath,
                'scan_akta_kelahiran'  => $scanAktaPath,
                'scan_ijazah_terakhir' => $scanIjazahPath,
                'scan_ktp_ayah'        => $scanKtpAyahPath,
                'scan_ktp_ibu'         => $scanKtpIbuPath,

                'status_pendaftaran'   => 'pending'
            ]);

            return redirect()->route('siswa.pendaftaran.status')
                             ->with('success', 'Pendaftaran berhasil! Nomor pendaftaran Anda: ' . $no_pendaftaran);

        } catch (\Exception $e) {
            Log::error('Pendaftaran Error: ' . $e->getMessage());

            // Hapus semua file yang sempat terupload jika query insert database gagal
            foreach ($uploadedPaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            return back()->withInput()
                         ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    // Status pendaftaran
    public function status()
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            return redirect()->route('siswa.pendaftaran.create')
                             ->with('info', 'Silakan lengkapi pendaftaran terlebih dahulu');
        }

        return view('siswa.pendaftaran.status', compact('siswa'));
    }
}