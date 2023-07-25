<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use App\Traits\SearchTrait;
use App\Traits\Sortable;

class PricingAddonFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'name', 'is_customer_visible', 'is_enabled', 'conditional_variable'
    ];

    protected $search = [
        'name', 'pricing_type', 'conditional_variable'
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

    public function conditionalVariable($conditional_variable)
    {
        return $this->where('conditional_variable', $conditional_variable);
    }

    public function isCustomerVisible($visibility)
    {
        return $this->where('is_customer_visible', $visibility);
    }

    public function isEnabled($visibility)
    {
        return $this->where('is_enabled', $visibility);
    }
}
