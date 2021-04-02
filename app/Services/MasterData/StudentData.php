<?php
//
namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebResponse;
use App\Models\PointRecord;
use App\Models\Siswa;
use App\Models\User;
use App\Utils\ObjectUtil;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StudentData extends BaseMasterData
{
    public function __construct(Filter $filter = null)
    {
        parent::__construct('siswa', $filter);
    }
    protected function queryObject()
    {
        return Siswa::with('user', 'kelas.sekolah');
    }
    public function list() : WebResponse
    {
        DB::enableQueryLog();
        $query = $this->queryObject();
         
        $filter_class = $this->getFieldsFilter('class_id') != "ALL" ? $this->getFieldsFilter('class_id'): null;
        $filter_name = $this->getFieldsFilter('name');
        $with_point_record = $this->getFieldsFilter('with_point_record'); 

        $query->leftJoin('users', 'users.nis', '=', 'siswa.nis');
        $query->leftJoin('kelas', 'kelas.id', '=', 'siswa.kelas_id');
        $query->where('users.name', 'like', '%'.$filter_name.'%');
        if (!is_null($filter_class)) {
            $query->where('siswa.kelas_id', '=', $filter_class);
        }
        
        $query->orderBy('kelas.level', 'asc');
        $query->orderBy('kelas.rombel', 'asc');
        $query->orderBy('users.name', 'asc');
        $queryCount = clone $query;
        $this->setLimitOffset($query);
        $students = $query->distinct()->get(['siswa.*']);
        if (sizeof($students) > 0) {
            $this->hidePassword($students);
        }
        // dd(json_encode(DB::getQueryLog()));
        $response = $this->generalResponse();
        
        $response->totalData = $queryCount->distinct()->count('siswa.id');
        if ($students->count() > 0 && $with_point_record == true) {
            $response->item = $this->getSetPointRecords($students);
        }
            
        $response->items = $students->toArray();
        
        return $response;
    }

    private function getSetPointRecords(Collection $students) : array
    {
        //get point records
        $date_from = ObjectUtil::toDateString($this->filter->day, $this->filter->month, $this->filter->year);
        $date_to = ObjectUtil::toDateString($this->filter->dayTo, $this->filter->monthTo, $this->filter->yearTo);
        
        $date_expression = DB::raw("STR_TO_DATE(concat(point_records.day,'/',point_records.month,'/',point_records.year), '%d/%m/%Y')");
        $query = PointRecord::with('rule_point.category');
        $query->leftJoin('rule_points', 'rule_points.id', '=', 'point_records.point_id');
        $query->whereNull('dropped_at');
        $query->where($date_expression, '>=', $date_from);
        $query->where($date_expression, '<=', $date_to);
        $query->whereIn('student_id', $students->pluck('id')->toArray());
        $query->groupBy('student_id');
        
        $points = $query->get([ DB::raw('sum(rule_points.point) as point'),'student_id']);
        $this->mapPointsAndStudents($students, $points);
        return $points->toArray();
    }

    private function mapPointsAndStudents(Collection $students, Collection $points)
    {
        foreach ($students as $student) {
            foreach ($points as $point) {
                if ($student->id == $point->student_id) {
                    $student->point = $point->point;
                }
            }
        }
    }

    private function hidePassword(Collection $students)
    {
        foreach ($students as $student) {
            $student->user = User::forResponse($student->user);
        }
    }
}
