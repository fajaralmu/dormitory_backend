<?php

namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Models\PointRecord;
use Error;

class MedicalRecordData extends BaseMasterData
{
    public function __construct(Filter $filter = null)
    {
        parent::__construct('medicalrecords', $filter);
    }

    protected function queryObject()
    {
        return PointRecord::with('student');
    }

    public function update(WebRequest $webRequest): bool
    {
        throw new Error("Not implemented");
    }
}
