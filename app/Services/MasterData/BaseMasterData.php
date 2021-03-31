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

class BaseMasterData
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
        throw new Exception("NOT IMPLEMENTED");
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
            $result = DB::table($this->tableName)->insert($model->toArray());
            return $result;
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
        $item = $this->doGetById($webRequest->record_id);
        $response = new WebResponse();
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
        return DB::table($this->tableName)->where('id', '=', $record_id)->first();
    }
    public function doDeleteById($record_id)
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
            default:
                # code...
                throw new Error("Invalid model name: ".$modelName);
        }
    }
}
