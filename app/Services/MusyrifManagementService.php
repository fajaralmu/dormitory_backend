<?php

namespace App\Services;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Pegawai;
use App\Models\User;
use App\Services\MasterData\MusyrifData;
use Error;

//
define('ROLE_MUSYRIF_ASRAMA', 'musyrif_asrama');
class MusyrifManagementService
{
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
