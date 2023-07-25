<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class FavoriteFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'name', 'customer_id', 'created_at', 'updated_at'
    ];

    protected $search = [
        'name'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function name($net_total)
    {
        return $this->where('name', $net_total);
    }

    public function customerId($customer_id)
    {
        return $this->where('customer_id', $customer_id);
    }
}
