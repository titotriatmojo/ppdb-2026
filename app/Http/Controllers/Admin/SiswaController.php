<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // Menampilkan daftar siswa
    public function index()
    {
        $siswas = siswa::with(['user', 'jurusan'])->latest()->paginate(10);
        return view('admin.siswa.index', compact('siswas'));
    }

    // Menampilkan detail siswa
    public function show($id)
    {
        $siswa = siswa::with(['user', 'jurusan', 'pembayarans'])->findOrFail($id);
        return view('admin.siswa.show', compact('siswa'));
    }

    // Verifikasi pendaftaran siswa
    public function verifikasi(Request $request, $id)
    {
        $siswa = siswa::findOrFail($id);
        
        $request->validate([
            'status_pendaftaran' => 'required|in:diverifikasi,ditolak',
            'catatan_admin' => 'nullable|string'
        ]);

        $siswa->update([
            'status_pendaftaran' => $request->status_pendaftaran,
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diupdate');
    }

    // Mengelola status penerimaan
    public function updateStatus(Request $request, $id)
    {
        $siswa = siswa::findOrFail($id);
        
        $request->validate([
            'status_pendaftaran' => 'required|in:pending,diverifikasi,ditolak,diterima',
        ]);

        $siswa->update([
            'status_pendaftaran' => $request->status_pendaftaran,
        ]);

        return redirect()->back()->with('success', 'Status siswa berhasil diupdate');
    }

    // Hapus siswa
    public function destroy($id)
    {
        $siswa = siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }
}