<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Subscription extends Model
{
    use Uuid;

    protected $fillable = [
        'paket',
        'kupon',
        // 'data_pegawai_filename',
        'jumlah_siswa',
        'active',
        'active_hingga',
        'history',
    ];

    protected $casts = ['active' => 'boolean', 'history' => 'json'];

    protected $dates = ['active_hingga'];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}
