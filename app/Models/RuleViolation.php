<?php

namespace App\Models;

class RuleViolation extends BaseModel
{
    protected $table = 'student_rule_violation';

    protected $fillable = [
        
        'student_id',
        'tahun_ajaran',
        'semester',
        'name',
        'point',
        'description',
    ];

    protected $student_id;
    protected $tahun_ajaran;
    protected $semester;
    protected $name;
    protected $point;
    protected $description;

    public function student()
    {
        return $this->hasOne(Siswa::class, 'id', 'student_id');
    }
}
