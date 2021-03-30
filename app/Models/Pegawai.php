<?php

namespace App\Models;

use App\Traits\Uuid;

class Pegawai extends BaseModel
{
    use Uuid;

    protected $table = 'pegawai';

    protected $fillable = [
        'gender',
        'nik',
        'nip',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'telp',
        'jabatan',
        'riwayat_jabatan',
        'file_foto',
        'propinsi',
        'kabupaten_kota',
        'kecamatan',
        'kelurahan',
        'kodepos',
        'agama'
    ];

    protected $dates = ['tanggal_lahir'];

    protected $casts = ['jabatan' => 'array', 'riwayat_jabatan' => 'array', 'roles' => 'array'];

    // eager load for related models
    protected $with = ['user'];

    public function user()
    {
        return $this->hasOne(User::class, 'nip', 'nip');
    }

    public function yayasan()
    {
        return $this->belongsTo(Yayasan::class);
    }

    public function sekolahs()
    {
        return $this->belongsToMany(Sekolah::class)->using(PegawaiSekolah::class);
    }
}
