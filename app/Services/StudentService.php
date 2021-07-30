<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\FollowUp;
use App\Models\Kelas;
use App\Models\MedicalRecords;
use App\Models\Pictures;
use App\Models\PointRecord;
use App\Models\RulePoint;
use App\Models\Siswa;
use App\Services\MasterData\PointRecordData;
use App\Utils\FileUtil;
use Error;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StudentService
{

    private ConfigurationService $configService;
    public function __construct()
    {
        $this->configService = app(ConfigurationService::class);
    }

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
    /**
     * insert and update
     */
    public function submitPointRecord(WebRequest $webRequest) : WebResponse
    {
        $model = $webRequest->pointRecord;
        if ($model->id) {
            if ($model->dropped_at) {
                throw new Error("Record has been dropped");
            }
            $updated = DB::table('point_records')->where('id', '=', $model->getId())->update($model->toArray()) == 1;
        } else {
            $model->save();
        }
        
        if (isset($webRequest->attachmentInfo) && !is_null($webRequest->attachmentInfo)) {
            if ($model->id) {
                $this->removeOldPicture($model->id);
            }
            $picture = new Pictures();
            $picture->point_record_id = $model->id;
            $picture->name = FileUtil::writeBase64File($webRequest->attachmentInfo->url, 'POINT_RECORD');
            $picture->save();
        }

        $response = new WebResponse();
        $response->item = PointRecord::with('rule_point.category', 'pictures', 'student')->find($model->id);
        return $response;
    }
    private function removeOldPicture(int $point_record_id)
    {
        $data = new PointRecordData();
        $data->deletePictures($point_record_id);
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

    public function followUp(WebRequest $webRequest) : WebResponse
    {
        $followUp = new FollowUp();
        $followUp->name = "TEST";
        $followUp->description = "test FOLLOW UP";

        $followUp->save();
        $pointRecord = PointRecord::with('follow_ups')->find($webRequest->record_id);
        if ($pointRecord->follow_ups->count() > 0) {
            throw new Error("Follow up exist");
        }
        $pointRecord->follow_ups()->attach($followUp);
        
        $response = new WebResponse();
        $response->item = $pointRecord;
        return $response;
    }

    public function followUpReminderList(): WebResponse
    {
        $config = $this->configService->getApplicationProfile();
        $SQL = 'SELECT 
                    r.student_id as STUDENT_ID,
                    sum(p.point) as TOTAL_POINT, 
                    count(fp.id) as FOLLOW_UP_COUNT
                from point_records r 
                    -- left join siswa s on s.id = r.student_id 
                    -- left join users u on u.nis = s.nis 
                    left join rule_points p on p.id = r.point_id 
                    left join follow_up_point_record fp on fp.point_record_id = r.id 
                group by r.student_id, r.dropped_at 
                having 
                    -- FOLLOW_UP_COUNT = ? and TOTAL_POINT < ?
                    TOTAL_POINT < ?
                    and r.dropped_at is null ';
                  
        $result = DB::select($SQL, [  $config->warning_point ]);
        // $result = DB::select($SQL, [0, -30]);
        $student_id_array = $this->getStudentIdArray($result);
       
        $response = new WebResponse();
        $response->items =  $this->mapStudentNames($result, $student_id_array);
        return $response;
    }

    private function mapStudentNames(array $records, array $array_of_id) : array {
        $students = Siswa::with('user', 'kelas.sekolah')->whereIn('id', $array_of_id)->get();
        foreach ($records as $record) {
            foreach ($students as $student) {
                if ($student->id == $record->STUDENT_ID) {
                    $record->student = $student;
                    break;
                }
            }
        }

        return $records;
    }

    private function getStudentIdArray(array $records) : array
    {
        $array = array();
        foreach ($records as $item) {
            array_push($array, $item->STUDENT_ID);
        }

        return $array;
    }
}
