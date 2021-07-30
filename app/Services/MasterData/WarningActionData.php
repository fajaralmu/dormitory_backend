<?php

namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\WarningAction;

class WarningActionData extends BaseMasterData
{
    public function __construct(Filter $filter = null)
    {
        parent::__construct('warning_action', $filter);
    }

    public function list() : WebResponse
    {
        $filterName = $this->getFieldsFilter('name') ?? "";
        $filterStudentName = $this->getFieldsFilter('student_name') ?? "";
        $wheres = array(
            ['warning_action.name', 'like', '%'.$filterName.'%'],
            ['users.name', 'like', '%'.$filterStudentName.'%']
        );
        $items = $this->queryList($wheres, 'warning_action.name');
        $result_count = $this->queryCount($wheres);
        
        $response = $this->generalResponse();

        $response->totalData = $this->total_data = $result_count;
        $response->items = $this->result_list = $items;
        return $response;
    }

    protected function withJoin($q)
    {
        $q->leftJoin('siswa', 'siswa.id', '=', 'warning_action.student_id');
        $q->leftJoin('users', 'users.nis', '=', 'siswa.nis');
        return $q;
    }

    public function update(WebRequest $webRequest): bool
    {
        return $this->doUpdate($webRequest->warningAction);
    }

    protected function queryObject()
    {
        return WarningAction::with('student.kelas.sekolah');
    }

    public function doGetById($record_id)
    {
        return $this->queryObject()->find($record_id);
    }
}
