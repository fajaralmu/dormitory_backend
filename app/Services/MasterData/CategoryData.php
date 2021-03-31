<?php
namespace App\Services\MasterData;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryData extends BaseMasterData
{
    public function __construct(Filter $filter = null)
    {
        parent::__construct('categories', $filter);
    }

    public function list() : WebResponse
    {
        $limit = $this->limit();
        $offset = $this->offset();
        $filterName = $this->getFieldsFilter('name') ?? "";
        $items = DB::select("select c.* from categories c where c.name like '%".$filterName."%' order by c.name  limit ".$limit." offset ".$offset);
        $result_count = DB::select("select count(*) as count from categories c where c.name like '%".$filterName."%'");
        $response = new WebResponse();

        $response->totalData = $this->total_data = $result_count[0]->count;
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
