<?php
namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\RulePoint;

class RulePointData extends BaseMasterData
{
    public function __construct(Filter $filter = null)
    {
        parent::__construct('rule_points', $filter);
    }
    public function list() : WebResponse
    {
        $filterName = $this->getFieldsFilter('name') ?? "";
        $wheres = array(['name', 'like', '%'.$filterName.'%']);
        $items = $this->queryList($wheres, 'name', 'asc');
        $result_count = $this->queryCount($wheres);
        $response = new WebResponse();

        $response->totalData = $this->total_data = $result_count;
        $response->items = $this->result_list = $items;
        
        return $response;
    }
    protected function queryObject()
    {
        return RulePoint::with('category');
    }

    public function update(WebRequest $webRequest): bool
    {
        $record = $webRequest->rulePoint;
        if (is_null($record->category_id) && !is_null($record->category)) {
            $record->category_id = $record->category->id;
            $record->category = null;
        }
        return $this->doUpdate($record);
    }

    public function doGetById($record_id)
    {
        $record = RulePoint::with('category')->find((int)$record_id);
        return $record;
    }
}
