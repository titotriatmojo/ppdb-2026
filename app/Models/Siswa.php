<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jurusan_id',
        'no_pendaftaran',
        'nama_lengkap',
        'nik',
        'nisn',
        'jenis_kelamin',
        'agama',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'asal_sekolah',
        'tempat_tinggal',
        'kewarganegaraan',

        // Data Ayah
        'nama_ayah_kandung',
        'ttl_ayah',
        'pta_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'no_hp_ayah',

        // Data Ibu
        'nama_ibu_kandung',
        'ttl_ibu',
        'pta_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'no_hp_ibu',

        // File & Dokumen
        'foto',
        'scan_kk',
        'scan_akta_kelahiran',
        'scan_ijazah_terakhir',
        'scan_ktp_ayah',
        'scan_ktp_ibu',

        // Status
        'status_pendaftaran',
        'catatan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'siswa_id', 'id');
    }
}