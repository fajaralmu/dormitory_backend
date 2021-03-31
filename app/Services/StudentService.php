<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Services\MasterData\StudentData;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function getStudentList(WebRequest $webRequest) : WebResponse
    {
        $data = new StudentData($webRequest->filter);
        return $data->list();
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
