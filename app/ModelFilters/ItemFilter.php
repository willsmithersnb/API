<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class ItemFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'item_list_id', 'formula_id', 'product_id', 'item_summary_id', 'item_no', 'name', 'price', 'cost'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'product_id' => 'sometimes|integer',
        'item_summary_id' => 'sometimes|integer',
        'price' => 'sometimes|integer',
        'cost' => 'sometimes|integer',
        'item_no' => 'sometimes|integer',
        'sort_col' => 'sometimes|in:name',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC'
    ];


    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function name($name)
    {
        return $this->where('name', 'ILIKE', '%' . $name . '%');
    }

    public function itemNo($item_no)
    {
        return $this->where('item_no', $item_no);
    }

    public function price($price)
    {
        return $this->where('price', $price);
    }

    public function cost($cost)
    {
        return $this->where('cost', $cost);
    }

    public function productId($product_id)
    {
        return $this->where('product_id', $product_id);
    }

    public function itemSummaryId($item_summary_id)
    {
        return $this->where('item_summary_id', $item_summary_id);
    }
}
