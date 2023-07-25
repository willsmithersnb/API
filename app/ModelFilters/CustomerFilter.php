<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class CustomerFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'name', 'customer_type'
    ];

    protected $search = [
        'name', 'customer_type'
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

    public function customerType($customer_type)
    {
        return $this->where('customer_type', 'ILIKE', '%' . $customer_type . '%');
    }
}
