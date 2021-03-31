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

    public function update(WebRequest $webRequest): bool
    {
        $record = $webRequest->rulePoint;
        $record->category_id = $record->category->id;
        return $this->doUpdate($record);
    }

    public function doGetById($record_id)
    {
        return RulePoint::find($record_id)->with('category');
    }
}
