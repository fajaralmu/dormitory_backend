<?php

namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\MedicalRecords;
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
        return MedicalRecords::with('student');
    }

    public function monthlyList(): array
    {
        $query = $this->queryObject();
        $query->where(
            [
                'student_id' => $this->getFieldsFilter('student_id'),
                'year' => $this->filter->year,
                'month' => $this->filter->month
            ]
        );
        $query->orderBy('day');
         
        $items = $query->get();
        return $items->toArray();
    }

    public function update(WebRequest $webRequest): bool
    {
        throw new Error("Not implemented");
    }
}