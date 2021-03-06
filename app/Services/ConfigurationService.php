<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\ApplicationProfile;
use App\Models\User;
use App\Utils\FileUtil;
use Illuminate\Support\Facades\DB;
use Throwable;

class ConfigurationService
{
    public function getApplicationProfile(): ApplicationProfile
    {
        // return (ApplicationProfile::defaultModel());
        $existing = ApplicationProfile::with('division_head', 'school_director')->
            where(['code'=>ApplicationProfile::$CODE])->first();
        if (is_null($existing)) {
            $model = ApplicationProfile::defaultModel();
            $model->id = $model->save();
            $existing = $model;
        }

        try {
            $existing->division_head->user = User::forResponse($existing->division_head->user);
            $existing->school_director->user = User::forResponse($existing->school_director->user);
        } catch (Throwable $th) {

        }
        unset($existing->created_at);
        unset($existing->updated_at);
        $existing->semester = config('school.semester');
        $existing->tahun_ajaran = config('school.tahun_ajaran');

        return $existing;
    }

    public function update(WebRequest $webRequest) : WebResponse
    {
        $model = $webRequest->applicationProfile;
        $existing = ApplicationProfile::where(['code'=>ApplicationProfile::$CODE])->first();
        if ($model->warning_point) {
            $existing->warning_point = $model->warning_point;
        }
        if ($model->name) {
            $existing->name = $model->name;
        }
        if ($model->description) {
            $existing->description = $model->description;
        }
        if ($model->division_head_id) {
            $existing->division_head_id = $model->division_head_id;
        }
        if ($model->school_director_id) {
            $existing->school_director_id = $model->school_director_id;
        }
        if ($model->report_date) {
            $existing->report_date = $model->report_date;
        }
        if ($webRequest->attachmentInfo) {
            $existing->stamp = FileUtil::writeBase64File($webRequest->attachmentInfo->url, 'PROFILE');
        }
        if ($webRequest->attachmentInfo2) {
            $existing->school_director_signature = FileUtil::writeBase64File($webRequest->attachmentInfo2->url, 'PROFILE');
        }
        if ($webRequest->attachmentInfo3) {
            $existing->division_head_signature = FileUtil::writeBase64File($webRequest->attachmentInfo3->url, 'PROFILE');
        }
        if ($webRequest->attachmentInfo4) {
            $existing->dormitory_stamp = FileUtil::writeBase64File($webRequest->attachmentInfo4->url, 'PROFILE');
        }

        DB::table('application_profiles')->where('id', $existing->getId())->update($existing->toArray()) == 1;
        
        $response = new WebResponse();
        $response->item = $existing;
        return $response;

    }
}