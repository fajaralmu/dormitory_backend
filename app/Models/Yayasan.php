<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Yayasan extends Model
{
    use Uuid;

    protected $table = 'yayasan';

    protected $fillable = [
        'nama',
        'akta_notaris',
        'alamat',
        'telp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function subscription()
    // {
    //     return $this->belongsTo(Subscription::class);
    // }

    public function schools()
    {
        return $this->hasMany(Sekolah::class);
    }

    public function employees()
    {
        return $this->hasMany(Pegawai::class);
    }
}
