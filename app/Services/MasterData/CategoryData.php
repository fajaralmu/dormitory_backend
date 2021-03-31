<?php

namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Category;

class CategoryData extends BaseMasterData
{
    public function __construct(Filter $filter = null)
    {
        parent::__construct('categories', $filter);
    }

    public function list() : WebResponse
    {
        $filterName = $this->getFieldsFilter('name') ?? "";
        $wheres = array(['name', 'like', '%'.$filterName.'%']);
        $items = $this->queryList($wheres, 'name');
        $result_count = $this->queryCount($wheres);
        
        $response = $this->generalResponse();

        $response->totalData = $this->total_data = $result_count;
        $response->items = $this->result_list = $items;
        return $response;
    }

    public function update(WebRequest $webRequest): bool
    {
        return $this->doUpdate($webRequest->category);
    }

    public function doGetById($record_id)
    {
        return Category::find($record_id);
    }
}
