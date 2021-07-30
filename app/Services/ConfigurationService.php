<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\ApplicationProfile;
use Illuminate\Support\Facades\DB;

class ConfigurationService
{
    public function getApplicationProfile(): ApplicationProfile
    {
        // return (ApplicationProfile::defaultModel());
        $existing = ApplicationProfile::where(['code'=>ApplicationProfile::$CODE])->first();
        if (is_null($existing)) {
            $model = ApplicationProfile::defaultModel();
            $model->id = $model->save();
            $existing = $model;
        }
        return $existing;
    }

    public function update(WebRequest $webRequest) : WebResponse
    {
        $model = $webRequest->applicationProfile;
        $existing = $this->getApplicationProfile();
        if ($model->warning_point) {
            $existing->warning_point = $model->warning_point;
        }
        if ($model->name) {
            $existing->name = $model->name;
        }
        if ($model->description) {
            $existing->description = $model->description;
        }

        DB::table('application_profiles')->where('id', $existing->getId())->update($existing->toArray()) == 1;
        
        $response = new WebResponse();
        $response->item = $existing;
        return $response;

    }
}