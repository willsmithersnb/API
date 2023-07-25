<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class PricingRuleFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'name', 'condition', 'price', 'cost', 'has_custom_price'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'condition' => 'sometimes|string',
        'price' => 'sometimes|numeric',
        'cost' => 'sometimes|integer',
        'has_custom_price' => 'sometimes|boolean',
        'sort_col' => 'sometimes|in:name,condition.price,cost,has_custom_price',
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
        return $this->whereLike('name', '%' . $name . '%');
    }

    public function condition($condition)
    {
        return $this->whereLike('condition', '%' . $condition . '%');
    }

    public function price($price)
    {
        return $this->where('price', $price);
    }

    public function cost($cost)
    {
        return $this->where('cost', $cost);
    }

    public function hasCustomPrice($hasCustomPrice)
    {
        return $this->where('has_custom_price', $hasCustomPrice);
    }
}
