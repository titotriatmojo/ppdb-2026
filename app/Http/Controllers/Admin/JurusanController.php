<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusans = Jurusan::withCount(['siswas', 'siswaDiterima'])->paginate(10);
        return view('admin.jurusan.index', compact('jurusans'));
    }

    public function create()
    {
        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|unique:jurusans',
            'nama_jurusan' => 'required|string',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|integer|min:1'
        ]);

        Jurusan::create($request->all());

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function edit($id)
    {
        // PERUBAHAN: Load dengan count siswa untuk validasi kuota
        $jurusan = Jurusan::withCount(['siswas', 'siswaDiterima'])->findOrFail($id);
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, $id)
    {
        // PERUBAHAN: Load dengan count siswa untuk validasi kuota
        $jurusan = Jurusan::withCount(['siswas', 'siswaDiterima'])->findOrFail($id);
        
        $request->validate([
            'kode_jurusan' => 'required|string|unique:jurusans,kode_jurusan,' . $id,
            'nama_jurusan' => 'required|string',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive'
        ]);

        // Validasi status: Tidak boleh non-active jika ada siswa (pending atau diterima)
        // Kita tetap gunakan siswas_count untuk status aktif/nonaktif agar aman
        if ($request->status == 'inactive' && $jurusan->siswas_count > 0) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Tidak dapat menonaktifkan jurusan yang masih memiliki siswa terdaftar (' . $jurusan->siswas_count . ' siswa, termasuk pending).');
        }

        // PERUBAHAN: Validasi kuota tidak boleh lebih kecil dari siswa DITERIMA
        if ($request->kuota < $jurusan->siswa_diterima_count) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Kuota tidak boleh lebih kecil dari jumlah siswa yang sudah DITERIMA (' . $jurusan->siswa_diterima_count . ' siswa)');
        }

        $jurusan->update($request->all());

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil diupdate');
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        
        // OPSIONAL: Cek apakah ada siswa yang terdaftar sebelum hapus
        if ($jurusan->siswas()->count() > 0) {
            return redirect()->back()
                           ->with('error', 'Tidak dapat menghapus jurusan yang masih memiliki siswa terdaftar');
        }
        
        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil dihapus');
    }
}