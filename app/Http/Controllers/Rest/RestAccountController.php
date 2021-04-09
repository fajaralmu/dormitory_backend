<?php

namespace App\Http\Controllers\Rest;

use App\Dto\WebResponse;
use App\Models\User;
use App\Services\AuthService;
use App\Services\ConfigurationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class RestAccountController extends BaseRestController
{
    private AuthService $authService;
    private ConfigurationService $configurationService;
     
    public function __construct(AuthService $authService, ConfigurationService $configurationService)
    {
        $this->authService = $authService;
        $this->configurationService = $configurationService;
    }
    public function requestId(Request $request) : JsonResponse
    {
        $apy = null;
        try {
            $token = JWTAuth::getToken();
            out("TOKEN: ".$token);
            $apy = JWTAuth::getPayload()->toArray();
        } catch (Throwable $th) {
            //
            $apy = $th->getMessage();
        }
        try {
            $response = new WebResponse();
            $response->item = $apy;
            $response->message = Str::random(10);
            $response->profile = $this->configurationService->getApplicationProfile();
            $response->loggedIn = is_null($request->user()) == false;
            if ($response->loggedIn) {
                $response->user = User::forResponse($request->user());
            }
            return parent::jsonResponseAndResendToken($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function login(Request $request) : JsonResponse
    {
        // $payload = parent::getWebRequest($request);
        try {
            $api_token = $this->authService->login($request);
            $response = new WebResponse();
            $response->user = User::forResponse($request->user());
            return parent::jsonResponse($response, $this->headerApiToken($api_token));
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function logout(Request $request) : JsonResponse
    {
        try {
            $response = $this->authService->logout();
            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    // public function register(Request $request)
    // {
    //     try {
    //         $payload = parent::getWebRequest($request);
    //         $response = $this->authService->register($payload);
           
    //         return parent::jsonResponse($response);
    //     } catch (Throwable $th) {
    //         return parent::errorResponse($th);
    //     }
    // }
}
