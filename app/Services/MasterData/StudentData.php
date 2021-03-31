<?php

namespace App\Services\MasterData;
use Illuminate\Support\Arr;
use App\Dto\Filter;
use App\Dto\WebResponse;
use App\Models\Siswa;
use App\Models\User;
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
         
        $query->join('users', 'users.nis', '=', 'siswa.nis');
        $query->leftJoin('kelas', 'kelas.id', '=', 'siswa.kelas_id');
        $query->where('users.name', 'like', '%'.$filter_name.'%');
        if (!is_null($filter_class)) {
            $query->where('siswa.kelas_id', '=', $filter_class);
        }
        // $query->where('kelas.id', '!=', 'null');
        
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
        $response->items = $students->toArray();
        $response->totalData = $queryCount->distinct()->count('siswa.id');

        
        return $response;
    }

    private function hidePassword(Collection $students)
    {
        $nis_array = [];
        foreach ($students as $student) {
            $student->user = User::forResponse($student->user);
        }
       
    }

}
