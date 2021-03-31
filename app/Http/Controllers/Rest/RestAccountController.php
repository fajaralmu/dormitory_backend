<?php

namespace App\Http\Controllers\Rest;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\User;
use App\Services\AccountService;
use App\Services\ConfigurationService;
use App\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class RestAccountController extends BaseRestController
{
    private AccountService $account_service;
    private ConfigurationService $configurationService;
     
    public function __construct(AccountService $account_service, ConfigurationService $configurationService)
    {
        $this->account_service = $account_service;
        $this->configurationService = $configurationService;
    }
    public function requestId(Request $request) : JsonResponse
    {
        try {
            $response = new WebResponse();
            $response->message = Str::random(10);
            $response->profile = $this->configurationService->getApplicationProfile();
            $response->loggedIn = is_null($request->user()) == false;
            if ($response->loggedIn) {
                $response->user = User::forResponse($request->user());
            }
            return parent::jsonResponseAndResendToken($response, $request);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function login(Request $request) : JsonResponse
    {
        // $payload = parent::getWebRequest($request);
        try {
            $api_token = $this->account_service->loginAttemp($request);
            $user = $request->user();
            $response = new WebResponse();
            $response->user = User::forResponse($user);
            return parent::jsonResponse($response, $this->headerApiToken($api_token));
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function logout(Request $request) : JsonResponse
    {
        try {
            $response = $this->account_service->logout($request->user());
            // $response->user = null;
            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    // public function register(Request $request)
    // {
    //     try {
    //         $payload = parent::getWebRequest($request);
    //         $response = $this->account_service->register($payload);
           
    //         return parent::jsonResponse($response);
    //     } catch (Throwable $th) {
    //         return parent::errorResponse($th);
    //     }
    // }
}
