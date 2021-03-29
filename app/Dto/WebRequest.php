<?php
namespace App\Dto;

use App\Models\Pegawai;
use App\Models\User;

class WebRequest
{
    public User $user;
    public Pegawai $employee;
}