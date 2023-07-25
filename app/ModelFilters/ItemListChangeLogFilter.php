<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class ItemListChangeLogFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'created_at'
    ];

    const FILTERABLE_COLUMNS = [
        'item_list_id' => 'sometimes|integer',
        'user_id' => 'sometimes|integer',
        'is_visible_to_customer' => 'sometimes|boolean',
    ];

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function itemListId($item_list_id)
    {
        return $this->where('item_list_id', $item_list_id);
    }

    public function userId($user_id)
    {
        return $this->where('user_id', $user_id);
    }

    public function isVisibleToCustomer($is_visible_to_customer)
    {
        return $this->where('is_visible_to_customer', $is_visible_to_customer);
    }
}
