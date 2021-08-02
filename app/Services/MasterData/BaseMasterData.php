<?php
namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\BaseModel;
use Error;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

abstract class BaseMasterData
{
    protected string $tableName;
    protected ?Filter $filter;

    //result
    protected array $result_list;
    protected int $total_data;

    public function __construct(string $tableName, Filter $filter = null)
    {
        $this->tableName = $tableName;
        if (!is_null($filter)) {
            $this->filter = $filter;
        }
    }
    protected function queryObjectLimitOffset()
    {
        $q = $this->queryObject();
        $this->setLimitOffset($q);
        return $q;
    }
    public function limit() :int
    {
        return $this->filter->limit;
    }
    public function offset() :int
    {
        return $this->filter->limit * $this->filter->page;
    }
    public function list():WebResponse
    {
        throw new Exception("list() NOT IMPLEMENTED");
    }
    protected function queryObject()
    {
        return DB::table($this->tableName);
    }
    protected function setLimitOffset($query)
    {
        if ($this->offset() > 0) {
            $query->skip($this->offset());
        }
        if ($this->limit() > 0) {
            $query->take($this->limit());
        }
    }
    protected function queryList(array $wheres, string $orderBy = null, string $orderType = 'asc') : array
    {
        $query = $this->queryObject();
        $this->withJoin($query);
        foreach ($wheres as $where) {
            $query->where($where[0], $where[1], $where[2]);
        }
        if (!is_null($orderBy)) {
            if ($orderType == 'asc') {
                $query->orderBy($orderBy);
            } else {
                $query->orderByDesc($orderBy);
            }
        }

        $this->setLimitOffset($query);
        return $query->get($this->tableName.'.*')->toArray();
    }
    protected function withJoin($q)
    {
        return $q;
    }
    protected function queryCount(array $wheres, $count_field = null) : int
    {
        $query = $this->queryObject();
        $this->withJoin($query);
        foreach ($wheres as $where) {
            $query->where($where[0], $where[1], $where[2]);
        }
        if (!is_null($count_field)) {
            return $query->count($count_field);
        }
        return $query->count();
    }
    protected function generalResponse(): WebResponse
    {
        $response = new WebResponse();
        if (isset($this->filter)) {
            $response->filter = $this->filter;
        }
        return $response;
    }
    protected function getFieldsFilter(string $key)
    {
        if (is_null($this->filter) || is_null($this->filter->fieldsFilter)) {
            return null;
        }
        return Arr::has($this->filter->fieldsFilter, $key) ? Arr::get($this->filter->fieldsFilter, $key) : null;
    }
    public function doUpdate(BaseModel $model) : bool
    {
        if (is_null($model->getId())) {
            // $result = DB::table($this->tableName)->insert($model->toArray());
            
            return $model->save();
        } else {
            return DB::table($this->tableName)->where('id', '=', $model->getId())->update($model->toArray()) == 1;
        }
        // return $model->save();
    }
    public function update(WebRequest $webRequest) :bool
    {
        throw new Error("update method is not implemented");
    }
    public function getById(WebRequest $webRequest):WebResponse
    {
        
        $response = new WebResponse();
        $item = $this->doGetById($webRequest->record_id);
        if (is_null($item)) {
            throw new Error("Data Not Found");
        }
        $response->item = $item;
        return $response;
    }
    public function delete(WebRequest $webRequest):WebResponse
    {
        $result = $this->doDeleteById($webRequest->record_id);
        $response = new WebResponse();
        if ($result == 1) {
            return $response;
        }
        throw new Error("Delete row failed");
    }

    public function doGetById($record_id)
    {
        return $this->queryObject()->where('id', '=', $record_id)->first();
    }
    public function doDeleteById($record_id) : bool
    {
        return DB::table($this->tableName)->where('id', '=', $record_id)->delete($record_id);
    }

    /**
     * Get the value of result_list
     */
    public function getResultList()
    {
        return $this->result_list;
    }

    /**
     * Get the value of total_data
     */
    public function getTotalData()
    {
        return $this->total_data;
    }

    public static function getInstance(string $modelName, Filter $filter = null) : BaseMasterData
    {
        switch ($modelName) {
            case 'category':
                return new CategoryData($filter);
            case 'student':
                return new StudentData($filter);
            case 'employee':
                return new MusyrifData($filter);
            case 'rulepoint':
                return new RulePointData($filter);
            case 'pointrecord':
                return new PointRecordData($filter);
            case 'medicalrecords':
                return new MedicalRecordData($filter);
            case 'warningaction':
                return new WarningActionData($filter);
            case 'categorypredicate':
                return new CategoryPredicateData($filter);
            default:
                # code...
                throw new Error("Invalid model name: ".$modelName);
        }
    }
}
