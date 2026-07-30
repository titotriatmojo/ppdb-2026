<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    private $biayaPendaftaran = 250000;

    // Menampilkan riwayat pembayaran
    public function index()
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            return redirect()->route('siswa.pendaftaran.create')
                           ->with('info', 'Silakan lengkapi pendaftaran terlebih dahulu');
        }

        $pembayarans = $siswa->pembayarans()->latest()->get();

        return view('siswa.pembayaran.index', compact('pembayarans', 'siswa'));
    }

    // Form upload pembayaran
    public function create()
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            return redirect()->route('siswa.pendaftaran.create')
                           ->with('info', 'Silakan lengkapi pendaftaran terlebih dahulu');
        }

        $biaya = $this->biayaPendaftaran;
        return view('siswa.pembayaran.create', compact('siswa', 'biaya'));
    }

    // Simpan pembayaran
    public function store(Request $request)
    {
        $siswa = auth()->user()->siswa;

        $request->validate([
            'tanggal_bayar' => 'required|date',
            'bukti_pembayaran' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048'
        ]);

        // Generate nomor pembayaran
        $no_pembayaran = 'PAY' . date('Ymd') . str_pad(Pembayaran::count() + 1, 4, '0', STR_PAD_LEFT);

        // Upload bukti pembayaran
        $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

        Pembayaran::create([
            'siswa_id' => $siswa->id,
            'no_pembayaran' => $no_pembayaran,
            'jumlah' => $this->biayaPendaftaran,
            'tanggal_bayar' => $request->tanggal_bayar,
            'bukti_pembayaran' => $buktiPath,
            'status' => 'pending'
        ]);

        return redirect()->route('siswa.pembayaran.index')
                       ->with('success', 'Pembayaran berhasil diupload dengan nomor: ' . $no_pembayaran);
    }

    // Detail pembayaran
    public function show($id)
    {
        $siswa = auth()->user()->siswa;
        $pembayaran = Pembayaran::where('siswa_id', $siswa->id)
                                ->findOrFail($id);

        return view('siswa.pembayaran.show', compact('pembayaran'));
    }
}