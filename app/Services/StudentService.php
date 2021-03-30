<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class StudentService
{
    public function getStudentList(WebRequest $webRequest) : WebResponse
    {
        $filter = $webRequest->filter;
        $limit = $filter->limit;
        $fieldsFilter = $filter->fieldsFilter;
        $filter_class = Arr::has($fieldsFilter, 'class_id') &&  $fieldsFilter['class_id']!= "ALL" ? $fieldsFilter['class_id'] : null;
        $filter_name = Arr::has($fieldsFilter, 'name') ? $fieldsFilter['name'] : '';
        $offset = $filter->limit * $filter->page;
        $students = DB::select(
            "select distinct s.nis, s.id, CONCAT(k.level, k.rombel) as class_name,
            UPPER(sk.nama) as school_name
            from siswa s 
            left join kelas k on k.id = s.kelas_id 
            left join sekolah sk on sk.id = k.sekolah_id
            left join users u on u.nis = s.nis 
            where u.name like '%".$filter_name."%'
            ". (!is_null($filter_class) ? ' and k.id = "'.$filter_class.'"':"") ."
            order by k.level, k.rombel, u.name asc limit ".$limit." offset ".$offset
        );
        $count_result = DB::select(
            "select count(distinct s.id) as count
            from siswa s 
            left join kelas k on k.id = s.kelas_id 
            left join sekolah sk on sk.id = k.sekolah_id
            left join users u on u.nis = s.nis 
            where u.name like '%".$filter_name."%'
            ". (!is_null($filter_class) ? ' and k.id = "'.$filter_class.'"':"") ."
           "
        );
        if (sizeof($students) > 0) {
            $this->populateWithUser($students);
        }
        $response = new WebResponse();
        $response->items = $students;
        $response->totalData = $count_result[0]->count;
        $response->filter = $filter;
        return $response;
    }

    private function populateWithUser(array $students)
    {
        $nis_array = [];
        foreach ($students as $student) {
            array_push($nis_array, $student->nis);
        }
        $users = User::whereIn('nis', $nis_array)->get();
        foreach ($students as $student) {
            foreach ($users as $user) {
                if ($student->nis == $user->nis) {
                    $student->user = User::forResponse($user);
                }
            }
        }
    }

    public function getClasses() : WebResponse
    {
        $classes = DB::select(
            'select 
            k.id, 
            CONCAT(k.level, k.rombel) as name, 
            UPPER(s.nama) as school_name 
            from kelas k left join sekolah s on k.sekolah_id = s.id 
            order by s.nama, k.level, k.rombel'
        );
        $response = new WebResponse();
        $response->items = $classes;
        return $response;
    }
}
