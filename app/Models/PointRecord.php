<?php
namespace App\Models;

class PointRecord extends BaseModel
{

    protected $table = 'point_records';

	protected $fillable = [
		'day',
        'month',
        'year',
        'time',
        'description',
        'student_id',
        'point_id',
        'location',
	];

	protected $id;
	protected $day;
	protected $month;
	protected $year;
	protected $time;
    protected $description;
    protected $student_id;
    protected $point_id;
    protected $location;
    public function rule_point()
    {
        return $this->hasOne(RulePoint::class, 'id', 'point_id');
    }
    public function student()
    {
        return $this->hasOne(Siswa::class, 'id', 'student_id');
    }
}