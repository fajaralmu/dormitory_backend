<?php
namespace App\Http\Controllers\Rest;

use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class RestReportController extends BaseRestController
{
    private ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function studentdata(Request $request, string $class_id) : JsonResponse
    {
        try {
            $response  = $this->reportService->getRaporData($class_id);
            return parent::jsonResponseAndResendToken($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function downloadReportData(Request $request, string $class_id)
    {
        return $this->reportService->downloadReportData($class_id);
    }
}
