<?php
namespace App\Dto;

use App\Models\ApplicationProfile;
use App\Models\Category;
use App\Models\CategoryPredicate;
use App\Models\MedicalRecords;
use App\Models\Pegawai;
use App\Models\PointRecord;
use App\Models\RulePoint;
use App\Models\User;
use App\Models\WarningAction;

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
    public RulePoint $rulePoint;
    public PointRecord $pointRecord;
    public MedicalRecords $medicalRecord;
    public WarningAction $warningAction;
    public CategoryPredicate $categoryPredicate;

    public ApplicationProfile $applicationProfile;

    public ?AttachmentInfo $attachmentInfo;

    public array $items;
}
