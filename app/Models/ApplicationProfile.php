<?php

namespace App\Models;

class ApplicationProfile extends BaseModel
{

	protected $table = 'application_profiles';

	protected $fillable = [
		'name',
		'code',
		'welcoming_message',
		'description',
		'warning_point',
		'division_head_id',
		'school_director_id',
		'semester',
		'tahun_ajaran',
		'report_date',
		'stamp',
		'division_head_signature',
		'school_director_signature'

	];

	protected $id;
	protected $name;
	protected $code;
	protected $welcoming_message;
	protected $description;
	protected $warning_point;
	protected $tahun_ajaran;
	protected $semester;
	protected $report_date;

	
	protected $division_head_id;
	protected $school_director_id;

	protected $stamp;
	protected $division_head_signature;
	protected $school_director_signature;
	

	public function division_head()
    {
        return $this->belongsTo(Pegawai::class);
    }
	public function school_director()
    {
        return $this->belongsTo(Pegawai::class);
    }

	public static string $CODE = "ASRAMA_KIIS";
	public static function defaultModel() : ApplicationProfile
	{
		$obj = new ApplicationProfile();
		$obj->name = 'Asrama KIIS';
		$obj->code = ApplicationProfile::$CODE;
		$obj->welcoming_message = "WELCOME";
		$obj->description = "Aplikasi Asrama";
		$obj->warning_point = -30;
		// $obj->setAttribute('name', "Asrama KIIS");
		// $obj->setAttribute('code', ApplicationProfile::$CODE);
		// $obj->setAttribute('welcoming_message', "Ahlan wa sahlan");
		// $obj->setAttribute('description', "Aplikasi Asrama");
		 
		return $obj;
	}
	
}
