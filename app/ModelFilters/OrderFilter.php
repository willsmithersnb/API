<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class OrderFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'name', 'delivery_date', 'created_at'
    ];

    protected $search = [
        'name', 'customer_id'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'customer_id' => 'sometimes|integer',
        'order_status_id' => 'sometimes|integer',
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

    public function deliveryDate($delivery_date)
    {
        return $this->where('delivery_date', $delivery_date);
    }

    public function status($status)
    {
        $statusOrderStatusIdMap = [
            'pending' => [0, 1, 2, 3, 4, 5, 6, 7, 8],
            'previous' => [9]
        ];

        if (array_key_exists($status, $statusOrderStatusIdMap)) {
            return $this->whereIn('order_status_id', $statusOrderStatusIdMap[$status]);
        } else {
            return $this;
        }
    }
}
