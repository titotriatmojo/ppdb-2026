<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $pengumumans = Pengumuman::where('is_active', true)
                                 ->latest()
                                 ->take(5)
                                 ->get();

        return view('siswa.dashboard', compact('siswa', 'pengumumans'));
    }
}