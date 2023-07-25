<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\SelectTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class CatalogFilter extends ModelFilter
{
    use Sortable;
    use SelectTrait;
    use SearchTrait;

    protected $sortableColumns = [
        "id", "number", "product_id"
    ];

    protected $search = [
        "number"
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function number($number)
    {
        return $this->where('number', 'ILIKE', '%' . $number . '%');
    }

    public function productId($product_id)
    {
        return $this->where('product_id', $product_id);
    }
}
