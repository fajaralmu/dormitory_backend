<?php
namespace App\Http\Controllers\Rest;

use App\Services\ConfigurationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class RestSettingController extends BaseRestController
{
    private ConfigurationService $configService;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
    }

    public function updateConfig(Request $request) : JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response  = $this->configService->update($webRequest);
            return parent::jsonResponseAndResendToken($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
}