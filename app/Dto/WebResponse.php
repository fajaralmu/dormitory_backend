<?php

namespace App\Dto;

use App\Models\ApplicationProfile;

class WebResponse
{
    public $code = '00';
    public $message = 'success';
    public array $items;
    public $item;

    public ?ApplicationProfile $profile;
}