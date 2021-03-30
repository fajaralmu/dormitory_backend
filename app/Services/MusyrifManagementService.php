<?php

namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Pegawai;
use App\Models\User;
use Error;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

//
define('ROLE_MUSYRIF_ASRAMA', 'musyrif_asrama');
class MusyrifManagementService
{
    public function getUserList(WebRequest $webRequest) : WebResponse
    {
        $filter = $webRequest->filter;
        $limit = $filter->limit;
        $offset = $filter->limit * $filter->page;
        $fieldsFilter = $filter->fieldsFilter;
        $filterName = '';
        $musyrif_only = false;
        try {
            if (Arr::has($fieldsFilter, 'name')) {
                $filterName = $fieldsFilter['name'];
            }
            if (Arr::has($fieldsFilter, 'musyrif_only')) {
                $musyrif_only = $fieldsFilter['musyrif_only'] ==  true;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        $filterRole = $musyrif_only ? "musyrif_asrama" : "";
        
        $response = new WebResponse();
        $employees =  DB::select(
            "select p.* from pegawai p left 
             join users u on u.nip = p.nip
             where (u.name like '%".$filterName."%' or u.email like '%".$filterName."%') 
             and u.roles like '%".$filterRole."%'
             order by u.name asc limit ".$limit." offset ".$offset
        );
        $count_result = DB::select(
            "select count(*) as count from pegawai left 
            join users u on u.nip = pegawai.nip
            where (u.name like '%".$filterName."%' or u.email like '%".$filterName."%') 
            and u.roles like '%".$filterRole."%' 
            "
        );
        if (sizeof($employees) > 0) {
            $this->populateWithUser($employees);
        }
        $response->filter = $filter;
        $response->items = $employees;
        $response->totalData =  $count_result[0]->count;
        return $response;
    }

    private function populateWithUser(array $employees)
    {
        $nip_array = [];
        foreach ($employees as $employee) {
            array_push($nip_array, $employee->nip);
        }
        $users = User::whereIn('nip', $nip_array)->get();
        foreach ($employees as $employee) {
            foreach ($users as $user) {
                if ($employee->nip == $user->nip) {
                    $employee->user = User::forResponse($user);
                }
            }
        }
    }

    public function activate(WebRequest $request) :WebResponse
    {
        $employee = Pegawai::find($request->employee_id);
        if (is_null($employee)) {
            throw new Error("Employee with id: $request->employee_id not found");
        }
        $user = User::where('nip', $employee->nip)->first();
        if (is_null($user)) {
            throw new Error("user not found");
        }
        if ($request->active == true) {
            $user->addRole(ROLE_MUSYRIF_ASRAMA);
        } else {
            $user->removeRole(ROLE_MUSYRIF_ASRAMA);
        }
       
        $response = new WebResponse();
        $response->item = $user;
        return $response;
    }
}