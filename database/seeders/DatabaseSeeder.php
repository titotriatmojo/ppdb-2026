<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status_akun' => 'aktif',
            'phone' => '081234567890',
            'address' => 'Kantor Pusat Bani Tamim',
            'email_verified_at' => now(),
        ]);

        // Create Jurusan
        $jurusans = [
            [
                'kode_jurusan' => 'SMP',
                'nama_jurusan' => 'SMP Pesantren',
                'deskripsi' => 'Program pendidikan tingkat SMP yang mengintegrasikan kurikulum nasional dengan pendidikan kepesantrenan dan pembinaan karakter Islami.',
                'kuota' => 100,
            ],
            [
                'kode_jurusan' => 'SMA',
                'nama_jurusan' => 'SMA Pesantren',
                'deskripsi' => 'Program pendidikan tingkat SMP yang mengintegrasikan kurikulum nasional dengan pendidikan kepesantrenan dan pembinaan karakter Islami.',
                'kuota' => 80,
            ],
        ];

        foreach ($jurusans as $jurusan) {
            Jurusan::create($jurusan);
        }
    }
}
