<?php
namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebResponse;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MusyrifData extends BaseMasterData
{
    public function __construct(Filter $filter = null)
    {
        parent::__construct('pegawai', $filter);
    }

    public function list() : WebResponse
    {
        $limit = $this->limit();
        $offset = $this->offset();
        $fieldsFilter = $this->filter->fieldsFilter;
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
        $response->filter = $this->filter;
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
}
