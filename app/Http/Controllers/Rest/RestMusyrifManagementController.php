<?php
namespace App\Http\Controllers\Rest;

use App\Dto\WebResponse;
use App\Services\MusyrifManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
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
}