<?php
namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Services\ReportService;
use Error;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RaporController extends Controller
{
    private ReportService $reportService;
    
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function rapor(Request $request, string $class_id)
    {
        $class = Kelas::with('sekolah')->find($class_id);
        if ($class == null) {
            throw new Error("Class with id:".$class_id." not found");
        }
        
        $data = $this->reportService->mapStudentsAndPoints($class_id);
        if (sizeof($data) == 0) {
            return null;
        }
        return view('rapor.index', [
            'class'        => $class,
            'items'        => $data,
            'semester'     => config('school.semester'),
            'tahun_ajaran' => config('school.tahun_ajaran')
        ]);
    }


}
