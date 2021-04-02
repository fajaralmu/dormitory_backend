<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Kelas;
use App\Models\PointRecord;
use App\Services\MasterData\StudentData;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentService
{
    public function dropPoint(WebRequest $webRequest) : WebResponse
    {
        $model = $webRequest->pointRecord;
        $record = PointRecord::find($model->id);
        if (is_null($record)) {
            throw new Exception("Data not found");
        }
        
        if (!is_null($model->dropped_at)) {
            $record->dropped_at = $model->dropped_at = explode('.', $model->dropped_at)[0];
            $record->save();
        } else {
            DB::table('point_records')->where('id', '=', $model->id)->update(['dropped_at'=> null]);
        }
        $response = new WebResponse();
        return $response;
    }
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
