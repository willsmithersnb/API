<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\SelectTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class FormulaFilter extends ModelFilter
{
    use Sortable;
    use SelectTrait;
    use SearchTrait;

    protected $sortableColumns = [
        "name", "id"
    ];

    protected $search = [
        "name"
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

    public function customerId($customer_id)
    {
        return $this->where('customer_id', $customer_id);
    }

    public function isCustomerSpecific($is_customer_specific = "false")
    {
        if(strtolower($is_customer_specific) == "true"){
            return $this->whereNotNull('customer_id');
        }else{
            return $this->whereNull('customer_id');
        }
    }
}
