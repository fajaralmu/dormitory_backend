<?php

namespace App\Models;

use App\Traits\Uuid;

class FollowUp extends BaseModel
{
    protected $table = 'follow_up';

	protected $fillable = [
		'name',
		'description',
	];

}
