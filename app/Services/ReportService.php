<?php
namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Dto\WebResponse;
use App\Models\Category;
use App\Models\CategoryPredicate;
use App\Models\Siswa;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ReportService
{
    private $letters  = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
    "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];



    public function getRaporData(string $class_id) : WebResponse
    {
        $items = $this->mapStudentsAndPoints($class_id);
        $response = new WebResponse();
        $response->items = $items;
        return $response;
    }

    private function mapStudentsAndPoints(string $class_id) : array
    {
        $categories     = Category::with('predicates')->get();
        $students       = Siswa::with('kelas.sekolah', 'user')->where('kelas_id', $class_id)->get();
        $mappedPoints   = $this->getStudentsPointMapped($class_id);
        $result         = [];

        foreach ($students as $student) {
            $data = [
                'student_id'    => $student->id,
                'name'          => Arr::get($student, 'user.name'),
                'class'         => Arr::get($student, 'kelas.level').Arr::get($student, 'kelas.rombel')
                                    .'  '. Arr::get($student, 'kelas.sekolah.nama'),
                'categories'    => []
            ];
            foreach ($categories as $category) {
                $initial_point = 100;
                $reduced_point = 0; //0 or negative
                try {
                    $reduced_point = $mappedPoints[$student->id][$category->id]->TOTAL_POINT;
                } catch (Throwable $th) {
                }
                $score = $initial_point + $reduced_point;
                $predicate = $category->getPredicate($score) ?? new CategoryPredicate();
                $data_categories = [
                    'category_id'       => $category->id,
                    'name'              => $category->name,
                    'initial_point'     => $initial_point,
                    'reduced_point'     => $reduced_point,
                    'total_point'       => $score,
                    'predicate_letter'  => $predicate->name,
                    'predicate_desc'    => $predicate->description,

                ];
                array_push($data['categories'], $data_categories);
            }

            array_push($result, $data);
        }

        return $result;
    }


    public function downloadReportData(string $class_id) : StreamedResponse
    {
        $data = $this->mapStudentsAndPoints($class_id);
        $headers = [
            'No', 'Siswa', 'Kelas'
        ];
        $report_data = [];
        $i = 1;
        $header_complete = false;
        foreach ($data as $item) {
            $report_data_item = [
                'No' => $i,
                'Siswa' => $item['name'],
                'Kelas' => $item['class'],

            ];
            $categories_data = $item['categories'];
            foreach ($categories_data as $category_data) {
                $category_id = $category_data['category_id'];
                if (!$header_complete) {
                    array_push($headers, $category_data['name'], "Pengurangan_". $category_id, 'Predikat_'.$category_id, 'Keterangan_'.$category_id);
                }

                $report_data_item[$category_data['name']] = $category_data['total_point'];
                $report_data_item['Predikat_'.$category_id] = $category_data['predicate_letter'];
                $report_data_item['Keterangan_'.$category_id] = $category_data['predicate_desc'];
                $report_data_item['Pengurangan_'.$category_id] = $category_data['reduced_point'];
            }
            $header_complete = true;

            array_push($report_data, $report_data_item);
            $i++;
        }
        $response = $this->writeExcel($report_data, $headers);

        return $this->fileResponse('Deteail-Rapor-Asrama-'.$class_id, $response);
    }

    private function getStudentsPointMapped(string $class_id) : array
    {
        $points = $this->getStudentsPoint($class_id);
        $result = [];
        foreach ($points as $point) {
            $studentId  = $point->STUDENT_ID;
            $categoryId = $point->CATEGORY_ID;
            if (!isset($result[$studentId]) || is_null($result[$studentId])) {
                $result[$studentId] = [];
            }
            $result[$studentId][$categoryId] = $point;
        }
        return $result;
    }

    private function fileResponse(string $fileName, StreamedResponse $response)
    {
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('content-disposition', 'attachment;filename="'. $fileName .'.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->headers->set('Access-Control-Expose-Headers', 'Content-Disposition');

        return $response;
    }


    private function getStudentsPoint(string $class_id) : array
    {
        $result = DB::select(
            'SELECT 
                rec.student_id AS STUDENT_ID,
                p.category_id AS CATEGORY_ID,
                sum(p.point) AS TOTAL_POINT 
            FROM `point_records` rec
            left join 
                rule_points p on p.id = rec.`point_id`
            left join
                siswa s on s.id = rec.student_id 
            group by 
                rec.student_id, p.category_id, rec.dropped_at, s.kelas_id
            having 
                rec.dropped_at is null  
                and s.kelas_id = ?
            ',
            [$class_id]
        );

        return $result;
    }

    public function writeExcel(array $reportData, array $headers) : StreamedResponse
    {
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $rowOffset = 2;
        $initialColumn = 1;

        for ($i = 0; $i < sizeof($headers); $i++) {
            $colName = $this->getCellName($initialColumn+$i, $rowOffset);
            $sheet->setCellValue($colName, strtoupper($headers[$i]));

            $columnDimension = $this->getCellRawName($initialColumn+$i, $rowOffset);
            $spreadsheet->getActiveSheet()->getColumnDimension($columnDimension['col'])->setAutoSize(true);
        }
        $rowOffset++;
        for ($i = 0; $i < sizeof($reportData); $i++) {
            $item = $reportData[$i];
            $column = $initialColumn;
            
            for ($h = 0; $h < sizeof($headers); $h++) {
                $value = Arr::get($item, $headers[$h], "");
                $colName = $this->getCellName($column, ($rowOffset + $i));
                $column++;
                $sheet->setCellValue($colName, $value);
            }
        }

        $writer = new Xlsx($spreadsheet);
        // $writer->save('excels/'.$finalFileName);

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        
        return $response;
    }

    //GetCellName returns column name
    private function getCellRawName(int $col, int $row)
    {
    
        $arrLen  = sizeof($this->letters);
        $name  = "";
        if ($col < $arrLen) {
            $name = $this->letters[$col];
        } else {
            $idx = 0;
            $idxCounter = 0;
            for ($i = 0; $i < ($col-$arrLen); $i++) {
                if ($i > 0 && $i % ($arrLen - 1) == 0) {
                    $idx++;
                    $idxCounter = 0;
                }
                $idxCounter++;
            }
            out("LEtters: ". $arrLen);
            $name = $this->letters[$idx]
                . $this->letters[$idxCounter];
        }
    
        return ['col'=> $name, 'row' => $row ];
    }

    private function getCellName($col, $row)
    {
        $raw = $this->getCellRawName($col, $row);
        return $raw['col'].$raw['row'];
    }

}
