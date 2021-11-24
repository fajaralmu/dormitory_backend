<?php
namespace App\Http\Controllers;

use App\Models\ApplicationProfile;
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

        $profile = ApplicationProfile::with('school_director', 'division_head')->where(
            ['code' => ApplicationProfile::$CODE]
        )->first();

        return view('rapor.index', [

            'class'             => $class,
            'items'             => $data,
            'semester'          => config('school.semester'),
            'tahun_ajaran'      => config('school.tahun_ajaran'),
            'stamp'             => $profile->stamp,
            'dormitory_stamp'   => $profile->dormitory_stamp,
            'report_date'       => $profile->report_date,

            'signatures'   => [
                'left'=> [
                    'as' => 'Direktur',
                    'name' => $profile->school_director->user->name,
                    'image' => $profile->school_director_signature
                ],
                'right' => [
                    'as' => 'Kepala Asrama',
                    'name' => $profile->division_head->user->name,
                    'image' => $profile->division_head_signature
                ]
            ],
        ]);
    }


}
