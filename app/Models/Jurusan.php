<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
        'deskripsi',
        'kuota',
        'status',
    ];

    public function siswas()
    {
        return $this->hasMany(siswa::class);
    }

    public function siswaDiterima()
    {
        return $this->hasMany(siswa::class)->whereHas('pembayarans', function($query) {
            $query->where('status', 'terverifikasi');
        });
    }

    public function getSisaKuotaAttribute()
    {
        // Hitung sisa kuota: Total Kuota - Jumlah siswa Diterima
        $terisi = $this->siswaDiterima()->count();
        return max(0, $this->kuota - $terisi);
    }
}