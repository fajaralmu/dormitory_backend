<?php
namespace App\Dto;

use App\Models\Pegawai;
use App\Models\User;

class WebRequest
{
    public Filter $filter;
    public User $user;

    //activate musyrif
    public ?string $employee_id;
    public ?bool $active;
}
