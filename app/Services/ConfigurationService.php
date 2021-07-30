<?php
namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\ApplicationProfile;

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
        $existing->warning_point = $model->warning_point;
        $existing->name = $model->name;

        $existing->save();

        $response = new WebResponse();
        $response->item = $existing;
        return $response;

    }
}