<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class PricingAddonTierFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'pricing_addon_id', 'condition_greater_than', 'price', 'cost'
    ];

    protected $search = [
        'pricing_addon_id'
    ];


    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function pricingAddonId($id)
    {
        $this->where('pricing_addon_id', $id);
    }
}
