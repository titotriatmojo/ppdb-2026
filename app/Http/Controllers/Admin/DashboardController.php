<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\siswa;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Admin Statistics
        $totalSiswa = siswa::count();
        $pendingVerifikasi = siswa::where('status_pendaftaran', 'pending')->count();
        $siswaDiterima = siswa::where('status_pendaftaran', 'diterima')->count();
        $pendingPembayaran = Pembayaran::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'pendingVerifikasi',
            'siswaDiterima',
            'pendingPembayaran'
        ));
    }
}