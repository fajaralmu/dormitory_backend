<?php
namespace App\Http\Controllers\Rest;

use App\Dto\WebResponse;
use App\Http\Controllers\Rest\BaseRestController;
use App\Services\MasterData\MedicalRecordData;
use App\Services\StudentPointService;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestStudentActivityManagementController extends BaseRestController
{
    private StudentService $studentService;
    private StudentPointService $studentPointService;
    public function __construct(StudentService $studentService, StudentPointService $studentPointService)
    {
        $this->studentService = $studentService;
        $this->studentPointService = $studentPointService;
    }
    public function rulecategories(Request $request) :JsonResponse
    {
        try {
            $response = ($this->studentService->getRuleCategories());
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
    public function classes(Request $request) : JsonResponse
    {
        try {
             $response = ($this->studentService->getClasses());
             return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
    public function droppoint(Request $request) : JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response = ($this->studentPointService->dropPoint($webRequest));
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
    public function undroppointall(Request $request) : JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response = ($this->studentPointService->undropPointAll($webRequest));
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
    public function droppointall(Request $request) : JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response = ($this->studentPointService->dropPointAll($webRequest));
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
    public function followup(Request $request) : JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response = ($this->studentService->followUp($webRequest));
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
    public function followupreminders(Request $request) : JsonResponse
    {
        try {
            $response = $this->studentService->followUpReminderList();
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
    public function submitpointrecord(Request $request) : JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response = $this->studentService->submitPointRecord($webRequest);
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th);
        }
    }

    public function submitmedicalrecord(Request $request) : JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $response = $this->studentService->submitMedicalRecord($webRequest);
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th);
        }
    }
    public function monthlymedicalrecord(Request $request) : JsonResponse
    {
        try {
            $webRequest = $this->getWebRequest($request);
            $medicalRecordData = new MedicalRecordData($webRequest->filter);
            $response = new WebResponse();
            $response->items = $medicalRecordData->monthlyList();
            return parent::jsonResponseAndResendToken($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th);
        }
    }
}
