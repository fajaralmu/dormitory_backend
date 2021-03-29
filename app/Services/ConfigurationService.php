<?php
namespace App\Services;

use App\Models\ApplicationProfile;

class ConfigurationService
{
    public function getApplicationProfile(): ApplicationProfile
    {
        $existing = ApplicationProfile::where(['code'=>ApplicationProfile::$CODE])->first();
        if (is_null($existing)) {
            $model = ApplicationProfile::defaultModel();
            $model->id = $model->save();
            $existing = $model;
        }
        return $existing;
    }
}