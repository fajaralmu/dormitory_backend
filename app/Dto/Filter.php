<?php

namespace App\Dto;

class Filter
{
    public int $page = 0;
    public int $limit = 5;
    public array $fieldsFilter = [];
    public $day;
    public $month;
    public $year;
    public $dayTo;
    public $monthTo;
    public $yearTo;
}