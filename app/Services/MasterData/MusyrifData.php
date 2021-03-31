<?php
namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebResponse;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Collection;

class MusyrifData extends BaseMasterData
{
    public function __construct(Filter $filter = null)
    {
        parent::__construct('pegawai', $filter);
    }
    protected function queryObject()
    {
        return Pegawai::with('user');
    }
    public function list() : WebResponse
    {
        $filterName = $this->getFieldsFilter('name');
        $musyrif_only =$this->getFieldsFilter('musyrif_only') ==  true;

        $filterRole = $musyrif_only ? "musyrif_asrama" : "";
        
        
        $query = $this->queryObject();
        $query->join('users', 'users.nip', '=', 'pegawai.nip');
        $query->where(
            function ($query) use ($filterName) {
                $query->orWhere('users.name', 'like', '%'.$filterName.'%');
                $query->orWhere('users.email', 'like', '%'.$filterName.'%');
            }
        );
        $query->where('users.roles', 'like', '%'.$filterRole.'%');
        $queryCount = clone $query;
        $this->setLimitOffset($query);

        $employees = $query->get(['pegawai.*']);
        $count_result = $queryCount->count();
        
        $this->hidePassword($employees);

        $response = $this->generalResponse();
        $response->items = $employees->toArray();
        $response->totalData =  $count_result;
        return $response;
    }

    private function hidePassword(Collection $employees)
    {
        foreach ($employees as $employee) {
            if (!is_null($employee->user)) {
                $employee->user = User::forResponse($employee->user);
            }
        }
    }
}
