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
            $response  = $this->musyrifManagemenetService->getUserList();
            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function activate(Request $request): JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response  = $this->musyrifManagemenetService->activate($webRequest);
            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
}