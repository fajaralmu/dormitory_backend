<?php
namespace App\Http\Controllers\Rest;

use App\Dto\WebResponse;
use App\Http\Controllers\Rest\BaseRestController;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestStudentActivityManagementController extends BaseRestController
{
    private StudentService $studentService;
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }
    public function classes(Request $request) : JsonResponse
    {
        try {
             $response = ($this->studentService->getClasses());
             return parent::jsonResponseAndResendToken($response, $request);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
    public function submitpointrecord(Request $request) : JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response = $this->studentService->submitPointRecord($webRequest);
            return parent::jsonResponseAndResendToken($response, $request);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th);
        }
    }
}
