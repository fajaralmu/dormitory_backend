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
		'warning_point'
	];

	// protected $id;
	// protected $name;
	// protected $code;
	// protected $welcoming_message;
	// protected $description;

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
