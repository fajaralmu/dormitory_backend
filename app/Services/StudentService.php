<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Category;
use App\Models\Kelas;
use App\Models\MedicalRecords;
use App\Models\Pictures;
use App\Models\PointRecord;
use App\Models\RulePoint;
use App\Utils\FileUtil;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function dropPoint(WebRequest $webRequest) : WebResponse
    {
        $model = $webRequest->pointRecord;
        $record = PointRecord::with('rule_point')->find($model->id);
        if (is_null($record)) {
            throw new Exception("Data not found");
        }
        if (!$record->rule_point->droppable) {
            throw new Exception("Cannot drop this item");
        }
        if (!is_null($model->dropped_at)) {
            $record->dropped_at = $model->dropped_at = explode('.', $model->dropped_at)[0];
            $record->save();
        } else {
            DB::table('point_records')->where('id', '=', $model->id)->update(['dropped_at'=> null]);
        }
        $response = new WebResponse();
        $response->item = PointRecord::find($model->id);
        return $response;
    }
    public function submitPointRecord(WebRequest $webRequest) : WebResponse
    {
        $model = $webRequest->pointRecord;
        $model->save();
        
        if (isset($webRequest->attachmentInfo) && !is_null($webRequest->attachmentInfo)) {
            $picture = new Pictures();
            $picture->point_record_id = $model->id;
            $picture->name = FileUtil::writeBase64File($webRequest->attachmentInfo->url, 'POINT_RECORD');
            $picture->save();
        }

        $response = new WebResponse();
        $response->item = PointRecord::with('rule_point.category', 'pictures', 'student')->find($model->id);
        return $response;
    }
    public function submitMedicalRecord(WebRequest $webRequest) : WebResponse
    {
        $model = $webRequest->medicalRecord;

        $existing = MedicalRecords::where([
            'day' => $model->day,
            'month' => $model->month,
            'year' => $model->year,
            'student_id' => $model->student_id
        ])->get();
        if (count($existing) > 0) {
            foreach ($existing as $ex) {
                $ex->delete();
            }
        }

        $model->save();
        $response = new WebResponse();
        $response->item = MedicalRecords::with('student')->find($model->id);
        return $response;
    }

    public function getClasses() : WebResponse
    {
        $classes = Kelas::with('sekolah')->orderBy('level')->orderBy('rombel')->get()->toArray();
        $response = new WebResponse();
        $response->items = $classes;
        return $response;
    }

    public function getRuleCategories() : WebResponse
    {
        $points = RulePoint::with('category')->get();
        $response = new WebResponse();
        $response->items =  $this->mapRuleAndCategories($points);
        return $response;
    }

    private function mapRuleAndCategories(Collection $points_collection):array
    {
        $points = $points_collection->toArray();
        $result = array();
        $map = array();
        foreach ($points as $point) {
            $category = Arr::get($point, 'category');
            $id = Arr::get($category, 'id');
            
            if (!isset($map[$id])) {
                $category['points'] = [];
                $map[$id] = $category;
            }
            unset($point['category']);
            array_push($map[$id]['points'], $point);
        }
        foreach ($map as $id => $category) {
            array_push($result, $category);
        }
        return $result;
    }

}
