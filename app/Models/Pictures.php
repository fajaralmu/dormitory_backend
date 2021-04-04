<?php
namespace App\Models;

class Pictures extends BaseModel
{

    protected $table = 'pictures';

	protected $fillable = [
        'name',
        'point_record_id'
	];

	protected $id;
	protected $point_record_id;

    public function point_record()
    {
        return $this->hasOne(PointRecord::class, 'id', 'point_record_id');
    }
}