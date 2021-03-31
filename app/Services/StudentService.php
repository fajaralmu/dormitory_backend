<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Kelas;
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
        $classes = Kelas::with('sekolah')->orderBy('level')->orderBy('rombel')->get()->toArray();
        $response = new WebResponse();
        $response->items = $classes;
        return $response;
    }
}
