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
        'dropped_at'
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
    protected $dropped_at;

    
    public function pictures()
    {
        return $this->hasMany(Pictures::class, 'point_record_id', 'id');
    }
    public function rule_point()
    {
        return $this->hasOne(RulePoint::class, 'id', 'point_id');
    }
    public function student()
    {
        return $this->hasOne(Siswa::class, 'id', 'student_id');
    }
    public function follow_ups()
    {
        return $this->belongsToMany(FollowUp::class)->using(PointRecordFollowUp::class);
    }
}
