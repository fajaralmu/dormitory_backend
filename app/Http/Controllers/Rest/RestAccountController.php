<?php

namespace App\Http\Controllers\Rest;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Services\AccountService;
use App\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class RestAccountController extends BaseRestController
{
    private AccountService $account_service;
     
    public function __construct(AccountService $account_service)
    {
        $this->account_service = $account_service;
    }
    public function requestId(Request $request) : JsonResponse
    {
        try {
            $response = new WebResponse();
            $response->message = Str::random(10);
            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function login(Request $request) : JsonResponse
    {
        $payload = parent::getWebRequest($request);
        try {
            $response = $this->account_service->loginAttemp($payload);
            $user = $response->user;
            $api_token = $user->api_token;
            
            return parent::jsonResponse($response, ['api_token'=>$api_token]);
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
    public function register(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->account_service->register($payload);
           
            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
}
