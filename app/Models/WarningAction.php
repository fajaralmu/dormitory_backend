<?php

namespace App\Models;

class WarningAction extends BaseModel
{
    protected $table = 'warning_action';

    protected $fillable = [
        'name',
        'student_id',
        'description',
    ];

    
    protected $id;
    protected $name;
    protected $student_id;
    protected $description;

    public function student()
    {
        return $this->hasOne(Siswa::class, 'id', 'student_id');
    }
    
}

