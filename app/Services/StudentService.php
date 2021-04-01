<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Kelas;
use App\Models\PointRecord;
use App\Services\MasterData\StudentData;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function submitPointRecord(WebRequest $webRequest) : WebResponse
    {
        $model = $webRequest->pointRecord;
        $model->save();
        
        $response = new WebResponse();
        $response->item = PointRecord::with('rule_point.category', 'student')->find($model->id);
        return $response;
    }

    public function getClasses() : WebResponse
    {
        $classes = Kelas::with('sekolah')->orderBy('level')->orderBy('rombel')->get()->toArray();
        $response = new WebResponse();
        $response->items = $classes;
        return $response;
    }
}
