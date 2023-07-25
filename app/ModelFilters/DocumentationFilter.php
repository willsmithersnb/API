<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\SelectTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class DocumentationFilter extends ModelFilter
{
    use Sortable;
    use SelectTrait;
    use SearchTrait;

    protected $sortableColumns = [
        "name"
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

    public function orderId($order_id)
    {
        return $this->where('order_id', $order_id);
    }
}
