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
            return $this->jsonResponse($this->studentService->getClasses());
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
    public function studentlist(Request $request) : JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response = $this->studentService->getStudentList($webRequest);
            return $this->jsonResponse($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th);
        }
    }
}
