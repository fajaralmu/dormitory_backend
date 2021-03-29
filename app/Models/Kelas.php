<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\TahunAjaranScope;
use App\Traits\Uuid;

class Kelas extends Model
{
	use Uuid;

	protected $table = 'kelas';

	protected $fillable = [
		'jurusan',
		'level',
		'rombel',
		'jumlah_siswa',
		'tahun_ajaran',
	];

	/**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TahunAjaranScope);

        // generate uuid on creating
        self::assignUuid();
    }

	public function sekolah()
	{
		return $this->belongsTo(Sekolah::class);
	}

	public function wali_kelas()
	{
		return $this->belongsTo(Pegawai::class, 'pegawai_id');
	}

	public function students()
	{
		return $this->hasMany(Siswa::class);
	}
}
