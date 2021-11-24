<?php
namespace App\Dto;

use App\Models\ApplicationProfile;
use App\Models\Category;
use App\Models\CategoryPredicate;
use App\Models\MedicalRecords;
use App\Models\Pegawai;
use App\Models\PointRecord;
use App\Models\RulePoint;
use App\Models\RuleViolation;
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
    public RuleViolation $ruleViolation;

    public ApplicationProfile $applicationProfile;

    public ?AttachmentInfo $attachmentInfo = null;
    public ?AttachmentInfo $attachmentInfo2 = null;
    public ?AttachmentInfo $attachmentInfo3 = null;
    public ?AttachmentInfo $attachmentInfo4 = null;

    public array $items;
}
