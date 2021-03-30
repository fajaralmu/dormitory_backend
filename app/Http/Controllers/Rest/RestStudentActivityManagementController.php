<?php
namespace App\Http\Controllers\Rest;

use App\Dto\WebResponse;
use App\Http\Controllers\Rest\BaseRestController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestStudentActivityManagementController extends BaseRestController
{
    public function studentlist(Request $request) : JsonResponse
    {
        try {
            
            return $this->jsonResponse(new WebResponse());
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th);
        }
    }
}
