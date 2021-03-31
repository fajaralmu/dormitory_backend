<?php
namespace App\Dto;

use App\Models\Category;
use App\Models\Pegawai;
use App\Models\User;

class WebRequest
{
    public ?Filter $filter;
    public User $user;

    public ?string $modelName;

    //activate musyrif
    public ?string $employee_id;
    public ?bool $active;
    public $record_id;

    //managements
    public Category $category;
}
