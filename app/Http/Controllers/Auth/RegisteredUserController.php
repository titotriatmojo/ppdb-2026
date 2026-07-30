<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form register blade kamu
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Eksekusi penyimpanan data ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'calon_siswa',             // Set default menjadi siswa
            'status_akun' => 'aktif',    // Set default status akun pending
            'phone' => null,               // Mengisi kolom di fillable agar tidak error null constraint
            'address' => null,             // Mengisi kolom di fillable agar tidak error null constraint
        ]);

        event(new Registered($user));

        // 3. Dialihkan kembali ke halaman login dengan alert status sukses
        return redirect()->route('login')->with('status', 'Registrasi berhasil! Akun Anda sedang dalam proses verifikasi admin. Silakan tunggu 1x24 jam.');
    }
}
