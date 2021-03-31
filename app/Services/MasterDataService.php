<?php
namespace App\Services;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Services\MasterData\BaseMasterData;
use Error;
use Illuminate\Support\Facades\DB;

class MasterDataService
{

    public function list(WebRequest $webRequest) : WebResponse
    {
        $modelName = $webRequest->modelName;
        $filter = $webRequest->filter;
        $data = BaseMasterData::getInstance($modelName, $filter);
        return $data->list();
    }
    public function getById(WebRequest $webRequest) : WebResponse
    {
        $modelName = $webRequest->modelName;
        $data = BaseMasterData::getInstance($modelName);
        return $data->getById($webRequest);
    }
    public function delete(WebRequest $webRequest) : WebResponse
    {
        $modelName = $webRequest->modelName;
        $data = BaseMasterData::getInstance($modelName);
        return $data->delete($webRequest);
    }
    public function update(WebRequest $webRequest) : WebResponse
    {
        $modelName = $webRequest->modelName;
        $data = BaseMasterData::getInstance($modelName);
        $updated = $data->update($webRequest);
        if ($updated) {
            return new WebResponse();
        }
        throw new Error("Update record failed");
    }

     
}