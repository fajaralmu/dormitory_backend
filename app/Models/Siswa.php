<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Siswa extends Model
{
    use Uuid;

    protected $table = 'siswa';

    protected $fillable = [
        'gender',
        'nik',
        'nisn',
        'nis',
        'tempat_lahir',
        'tanggal_lahir',
        'daerah_asal',
        'alamat',
        'anak_ke',
        'jumlah_saudara',
        'sekolah_asal',
        'nomor_ijazah',
        // 'kelas',
        // 'rombel',
        'riwayat_kelas',
        'telp',
        'graduated',
        'is_beasiswa',
        'virtual_account',
        'file_foto',
        'propinsi',
        'kabupaten_kota',
        'kecamatan',
        'kelurahan',
        'kodepos',
        'agama'
    ];

    protected $casts = [
        'riwayat_kelas' => 'array',
        'graduated' => 'boolean',
        'is_beasiswa' => 'boolean',
    ];

    protected $dates = ['tanggal_lahir'];

    // eager load for related models
    protected $with = ['user'];

    public function user()
    {
        return $this->hasOne(User::class, 'nis', 'nis');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
