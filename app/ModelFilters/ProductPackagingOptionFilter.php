<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class ProductPackagingOptionFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'product_id', 'created_at'
    ];

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];
}
