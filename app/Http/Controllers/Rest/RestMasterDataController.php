<?php
namespace App\Http\Controllers\Rest;

use App\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestMasterDataController extends BaseRestController
{
    private MasterDataService $masterDataService;

    public function __construct(MasterDataService $masterDataService)
    {
        $this->masterDataService = $masterDataService;
    }

    public function list(Request $request) : JsonResponse
    {
        try {
            $webRequest  = $this->getWebRequest($request);
            $response = $this->masterDataService->list($webRequest);
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th);
        }
    }
    public function getbyid(Request $request) : JsonResponse
    {
        try {
            $webRequest  = $this->getWebRequest($request);
            $response = $this->masterDataService->getById($webRequest);
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            throw $th;
            return $this->errorResponse($th);
        }
    }
    public function delete(Request $request) : JsonResponse
    {
        try {
            $webRequest  = $this->getWebRequest($request);
            $response = $this->masterDataService->delete($webRequest);
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th);
        }
    }
    public function update(Request $request) : JsonResponse
    {
        try {
            $webRequest  = $this->getWebRequest($request);
            $response =  $this->masterDataService->update($webRequest);
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            // throw $th;
            return $this->errorResponse($th);
        }
    }
    // Route::post('list', 'Rest\RestMasterDataController@list');
    // Route::post('update', 'Rest\RestMusyrifManagementController@update');
    // Route::post('delete', 'Rest\RestMusyrifManagementController@delete');
    // Route::post('getbyid', 'Rest\RestMusyrifManagementController@getbyid');
}
