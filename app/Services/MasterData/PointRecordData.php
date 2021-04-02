<?php

namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\PointRecord;
use App\Models\User;
use Error;
use Illuminate\Support\Collection;

$ALL = 'ALL';
class PointRecordData extends BaseMasterData
{

    public function __construct(Filter $filter)
    {
        parent::__construct('point_records', $filter);
    }

    public function update(WebRequest $webRequest): bool
    {
        throw new Error("Not implemented");
    }
    
    public function list(): WebResponse
    {
        $query = $this->queryObject();
        $query->leftJoin('siswa', 'siswa.id', '=', 'point_records.student_id');
        $query->leftJoin('users', 'users.nis', '=', 'siswa.nis');
        $query->leftJoin('rule_points', 'rule_points.id', '=', 'point_records.point_id');

        $filter_day = $this->getFieldsFilter('day');
        $filter_month = $this->getFieldsFilter('month');
        $filter_year = $this->getFieldsFilter('year');
        $filter_name = $this->getFieldsFilter('name');
        $filter_point_name = $this->getFieldsFilter('point_name');
        $filter_location = $this->getFieldsFilter('location');
        
        if (!is_null($filter_day) && $filter_day != 'ALL') {
            $query->where('day', '=', $filter_day);
        }
        if (!is_null($filter_month) && $filter_month != 'ALL') {
            $query->where('month', '=', $filter_month);
        }
        if (!is_null($filter_year) && $filter_year != '') {
            $query->where('year', '=', $filter_year);
        }
        if (!is_null($filter_name)) {
            $query->where('users.name', 'like', '%'.$filter_name.'%');
        }
        if (!is_null($filter_point_name)) {
            $query->where('rule_points.name', 'like', '%'.$filter_point_name.'%');
        }
        if (!is_null($filter_location)) {
            $query->where('location', 'like', '%'.$filter_location.'%');
        }

        $queryCount = clone $query;
        
        $query->orderBy('year', 'desc');
        $query->orderBy('month', 'desc');
        $query->orderBy('day', 'desc');
        $query->orderBy('time', 'desc');
        $this->setLimitOffset($query);

        $result_list =  $query->get(['point_records.*']);
        $this->hidePassword($result_list);

        $response = $this->generalResponse();
        $response->items = $result_list->toArray();
        $response->totalData = $queryCount->count();
        return $response;
    }
    private function hidePassword(Collection $items)
    {
        foreach ($items as $item) {
            try {
                $item->student->user = User::forResponse($item->student->user);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
    protected function queryObject()
    {
        return PointRecord::with('student', 'rule_point.category');
    }
}
