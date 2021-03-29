<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Sekolah extends Model
{
    use Uuid;

    protected $table = 'sekolah';

    protected $fillable = [
        'npsn',
        'nama',
        'jenjang',
        // 'kepemilikan',
        'jenis',
        'alamat',
        'telp',
        'data_siswa_filename',
        // 'data_pegawai_filename',
        'rombel',
        // 'jumlah_peserta_didik',
        // 'jumlah_pegawai',
        'options',
        'struktur_organisasi',
        'api_token',
    ];

    protected $casts = [
        'rombel' => 'array',
        'options' => 'json',
        'struktur_organisasi' => 'json',
    ];

    public function yayasan()
    {
        return $this->belongsTo(Yayasan::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Pegawai::class)->using(PegawaiSekolah::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Siswa::class, Kelas::class, 'sekolah_id', 'kelas_id');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
