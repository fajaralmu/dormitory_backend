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
	];

	protected $id;
	protected $name;
	protected $code;
	protected $welcoming_message;
	protected $description;

	public static string $CODE = "ASRAMA_KIIS";
	public static function defaultModel() : ApplicationProfile
	{
		$obj = new ApplicationProfile();
		$obj->setAttribute('name', "Asrama KIIS");
		$obj->setAttribute('code', ApplicationProfile::$CODE);
		$obj->setAttribute('welcoming_message', "Ahlan wa sahlan");
		$obj->setAttribute('description', "Aplikasi Asrama");
		
		return $obj;
	}
}
