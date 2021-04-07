<?php

namespace App\Models;

class MedicalRecords extends BaseModel
{
    protected $table = 'medical_records';

    protected $id;
    protected $day;
    protected $month;
    protected $year;
    protected $temperature_morning;
    protected $temperature_afternoon;
    protected $breakfast;
    protected $lunch;
    protected $dinner;
    protected $medicine_consumption;
    protected $genose_test;
    protected $antigen_test;
    protected $pcr_test;
    protected $description;
    protected $student_id;

    public function student()
    {
        return $this->hasOne(Siswa::class, 'id', 'student_id');
    }
}
