<?php

namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Pictures;
use App\Models\PointRecord;
use App\Models\User;
use App\Utils\FileUtil;
use App\Utils\ObjectUtil;
use Error;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

$ALL = 'ALL';
class PointRecordData extends BaseMasterData
{

    public function __construct(Filter $filter = null)
    {
        parent::__construct('point_records', $filter);
    }

    public function update(WebRequest $webRequest): bool
    {
        throw new Error("Not implemented");
    }
    
    public function list(): WebResponse
    {
        DB::enableQueryLog();
        $query = $this->queryObject();
        $query->leftJoin('siswa', 'siswa.id', '=', 'point_records.student_id');
        $query->leftJoin('users', 'users.nis', '=', 'siswa.nis');
        $query->leftJoin('rule_points', 'rule_points.id', '=', 'point_records.point_id');
        $query->leftJoin('categories', 'categories.id', '=', 'rule_points.category_id');

        
        $filter_name = $this->getFieldsFilter('name');
        $filter_point_name = $this->getFieldsFilter('point_name');
        $filter_location = $this->getFieldsFilter('location');
        $filter_category = $this->getFieldsFilter('category_name');
        $filter_dropped = $this->getFieldsFilter('dropped');
        
        //period
        $date_from = ObjectUtil::toDateString($this->filter->day, $this->filter->month, $this->filter->year);
        $date_to = ObjectUtil::toDateString($this->filter->dayTo, $this->filter->monthTo, $this->filter->yearTo);
        
        $date_expression = DB::raw("STR_TO_DATE(concat(point_records.day,'/',point_records.month,'/',point_records.year), '%d/%m/%Y')");
        
        $query->where($date_expression, '>=', $date_from);
        $query->where($date_expression, '<=', $date_to);

        if (!is_null($filter_name)) {
            $query->where('users.name', 'like', '%'.$filter_name.'%');
        }
        if (!is_null($filter_point_name)) {
            $query->where('rule_points.name', 'like', '%'.$filter_point_name.'%');
        }
        if (!is_null($filter_location)) {
            $query->where('location', 'like', '%'.$filter_location.'%');
        }
        if (!is_null($filter_category)) {
            $query->where('categories.name', 'like', '%'.$filter_category.'%');
        }
        if (!is_null($filter_dropped) && $filter_dropped != 'ALL') {
            if ($filter_dropped == 'true') {
                $query->whereNotNull('dropped_at');
            } elseif ($filter_dropped == 'false') {
                $query->whereNull('dropped_at');
            }
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

        // echo(json_encode(DB::getQueryLog()));

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
        return PointRecord::with('student', 'rule_point.category', 'pictures');
    }

    public function doDeleteById($record_id) : bool
    {
        $pictures = Pictures::where('point_record_id', '=', $record_id)->get();
        
        if (!is_null($pictures)) {
            foreach ($pictures as $picture) {
                $file_deleted = FileUtil::delete($picture->name, 'POINT_RECORD');
                // if ($file_deleted) {
                    $picture->delete();
                // }
            }
        }
        return parent::doDeleteById($record_id);
    }
}
