<?php

namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\RuleViolation;

class RuleViolationData extends BaseMasterData
{
    public function __construct(Filter $filter = null)
    {
        parent::__construct('student_rule_violation', $filter);
    }

    public function list() : WebResponse
    {
        $filterName = $this->getFieldsFilter('name') ?? "";
        $filterSemester = $this->getFieldsFilter('semester') ?? "";
        $filterTahunAjaran = $this->getFieldsFilter('tahun_ajaran') ?? "";
        $filterStudent = $this->getFieldsFilter('student_name') ?? "";
        $wheres = array(
            ['student_rule_violation.name', 'like', '%'.$filterName.'%'],
            ['student_rule_violation.semester', 'like', '%'.$filterSemester.'%'],
            ['student_rule_violation.tahun_ajaran', 'like', '%'.$filterTahunAjaran.'%'],
        );
        if (""!=$filterStudent) {
            array_push(
                $wheres,
                ['users.name', 'like', '%'.$filterStudent.'%']
            );
        }
        $items = $this->queryList($wheres, 'student_rule_violation.id', 'desc');
        $result_count = $this->queryCount($wheres, ("" != $filterStudent) ? 'siswa.id' : null);
        
        $response = $this->generalResponse();

        $response->totalData = $this->total_data = $result_count;
        $response->items = $this->result_list = $items;
        return $response;
    }

    protected function withJoin($q)
    {
        if (($this->getFieldsFilter('student_name') ?? "") != "") {
            $q->leftJoin('siswa', 'siswa.id', '=', 'student_rule_violation.student_id');
            $q->leftJoin('users', 'users.nis', '=', 'siswa.nis');
        }
        return $q;
    }

    public function update(WebRequest $webRequest): bool
    {
        return $this->doUpdate($webRequest->ruleViolation);
    }

    protected function queryObject()
    {
        return RuleViolation::with('student.kelas.sekolah')->distinct();
    }

    public function doGetById($record_id)
    {
        return $this->queryObject()->find($record_id);
    }
}
