<?php

namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\PointRecord;
use Error;
use Exception;
use Illuminate\Support\Facades\DB;

class StudentPointService
{

    public function dropPointAll(WebRequest $webRequest) :WebResponse
    {
        if (!$webRequest->items || sizeof($webRequest->items) == 0) {
            throw new Exception("Invalid record id");
        }
        $result = DB::statement(
            'UPDATE point_records rec
            left join rule_points p on p.id = rec.point_id 
            SET dropped_at = CURRENT_TIMESTAMP 
            where 
                p.droppable is true and rec.id in ('. join(',', $webRequest->items) .')'
            );
        $response = new WebResponse();
        $response->message = $result;
        return $response;
    }

    public function undropPointAll(WebRequest $webRequest) :WebResponse
    {
        if (!$webRequest->items || sizeof($webRequest->items) == 0) {
            throw new Exception("Invalid record id");
        }
        $result = DB::statement(
            'UPDATE point_records rec
            left join rule_points p on p.id = rec.point_id 
            SET dropped_at = null
            where 
                p.droppable is true and rec.id in ('. join(',', $webRequest->items) .')'
            );
        $response = new WebResponse();
        $response->message = $result;
        return $response;
    }

    public function dropPoint(WebRequest $webRequest) : WebResponse
    {
        $model = $webRequest->pointRecord;
        $record = PointRecord::with('rule_point')->find($model->id);
        if (is_null($record)) {
            throw new Exception("Data not found");
        }
        if ($record->rule_point->droppable != true) {
            throw new Exception("Cannot drop this item, id Poin:".$record->rule_point->id. ", id Pelanggaran: ".$model->id);
        }
        if (!is_null($model->dropped_at)) {
            $record->dropped_at = $model->dropped_at = explode('.', $model->dropped_at)[0];
            $record->save();
        } else {
            DB::table('point_records')->where('id', '=', $model->id)->update(['dropped_at'=> null]);
        }
        $response = new WebResponse();
        $response->item = PointRecord::find($model->id);
        return $response;
    }
}