<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class QuoteFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'name', 'created_at'
    ];

    protected $search = [
        'name', 'customer_id'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'customer_id' => 'sometimes|integer',
        'sort_col' => 'sometimes|in:name,customer_id',
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
        return $this->whereLike('name', $name);
    }

    public function customerId($customer_id)
    {
        return $this->whereLike('customer_id', $customer_id);
    }
}
