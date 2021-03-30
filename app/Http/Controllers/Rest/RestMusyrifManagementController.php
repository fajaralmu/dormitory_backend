<?php
namespace App\Http\Controllers\Rest;

use App\Dto\WebResponse;
use App\Services\MusyrifManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class RestMusyrifManagementController extends BaseRestController
{
    private MusyrifManagementService $musyrifManagemenetService;

    public function __construct(MusyrifManagementService $musyrifManagemenetService)
    {
        $this->musyrifManagemenetService = $musyrifManagemenetService;
    }
    public function userlist(Request $request): JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response  = $this->musyrifManagemenetService->getUserList($webRequest);
            return parent::jsonResponse($response, $this->resentToken($request));
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function activate(Request $request): JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response  = $this->musyrifManagemenetService->activate($webRequest);
            return parent::jsonResponse($response, $this->resentToken($request));
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
}