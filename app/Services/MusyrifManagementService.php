<?php

namespace App\Services;

use App\Dto\WebResponse;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class MusyrifManagementService
{
    public function getUserList() : WebResponse
    {
        $response = new WebResponse();
        $employees =  DB::select('select p.* from pegawai p left join users u on u.nip = p.nip order by u.name asc');
        if (sizeof($employees) > 0) {
            $this->populateWithUser($employees);
        }
        $response->items = $employees;
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
                    $employee->user = $user;
                }
            }
        }
    }
}