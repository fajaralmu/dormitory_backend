<?php

namespace App\Dto;

use App\Models\ApplicationProfile;
use App\Models\User;

class WebResponse
{
    public $code = '00';
    public $message = 'success';
    public array $items;
    public $item;
    public ?bool $loggedIn;

    public ?ApplicationProfile $profile;
    public ?User $user;

    public ?Filter $filter;
    public int $totalData = 0;
}