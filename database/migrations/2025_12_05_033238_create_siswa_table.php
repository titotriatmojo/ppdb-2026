<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::create('siswas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('jurusan_id')->constrained()->onDelete('cascade');
        $table->string('no_pendaftaran')->unique();
        $table->string('nama_lengkap');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('tempat_lahir');
        $table->date('tanggal_lahir');
        $table->text('alamat');
        $table->string('no_hp')->nullable();
        $table->string('asal_sekolah');
        //tambahan
        $table->enum('tempat_tinggal', ['Bersama Orang Tua', 'Wali', 'Kos', 'Asrama', 'Panti Asuhan']);
        $table->string('nik');
        $table->string('agama');
        $table->string('nisn');
        $table->enum('kewarganegaraan', ['Indonesia', 'Asing']);
        $table->string('nama_ayah_kandung');
        $table->string('ttl_ayah');
        $table->enum('pta_ayah', ['SD', 'SMP', 'SMA', 'D3', 'S1','S2','S3']);
        $table->enum('pekerjaan_ayah', ['Tidak Bekerja','Nelayan','Petani','Peternak','PNS/TNI/POLRI','Karyawan Swasta','Pedagang Kecil','Pedagang Besar','Wiraswasta','Buruh','Pensiunan','PPPK']);
        $table->enum('penghasilan_ayah', ['Rp0 - Rp2.000.000', 'Rp2.000.000 - Rp5.000.000','Rp5.000.000 - Rp20.000.000','> Rp20.000.000']);
        $table->string('no_hp_ayah');
        $table->string('nama_ibu_kandung');
        $table->string('ttl_ibu');
        $table->enum('pta_ibu', ['SD', 'SMP', 'SMA', 'D3', 'S1','S2','S3']);
        $table->enum('pekerjaan_ibu', ['Tidak Bekerja','Nelayan','Petani','Peternak','PNS/TNI/POLRI','Karyawan Swasta','Pedagang Kecil','Pedagang Besar','Wiraswasta','Buruh','Pensiunan','PPPK']);
        $table->enum('penghasilan_ibu', ['Rp0 - Rp2.000.000', 'Rp2.000.000 - Rp5.000.000','Rp5.000.000 - Rp20.000.000','> Rp20.000.000']);
        $table->string('no_hp_ibu');
        $table->string('scan_kk');
        $table->string('scan_akta_kelahiran');
        $table->string('scan_ijazah_terakhir');
        $table->string('scan_ktp_ayah');
        $table->string('scan_ktp_ibu');
        //tambahan
        $table->string('foto')->nullable();
        $table->enum('status_pendaftaran', ['pending', 'diverifikasi', 'ditolak', 'diterima'])->default('pending');
        $table->text('catatan_admin')->nullable();
        $table->timestamps();
    });
}
};
