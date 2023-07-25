<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class AddressFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'id', 'line_1', 'line_2', 'city', 'state', 'country', 'archived_at'
    ];

    const FILTERABLE_COLUMNS = [
        'line_1' => 'sometimes|string',
        'line_2' => 'sometimes|string',
        'city' => 'sometimes|string',
        'state' => 'sometimes|string',
        'country' => 'sometimes|string',
        'sort_col' => 'sometimes|in:line_1,line_2,city,state,country',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC'
    ];
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function line1($line_1)
    {
        return $this->whereLike('line_1', $line_1);
    }

    public function line2($line_2)
    {
        return $this->whereLike('line_2', $line_2);
    }

    public function city($city)
    {
        return $this->whereLike('city', $city);
    }

    public function state($state)
    {
        return $this->whereLike('state', $state);
    }

    public function country($country)
    {
        return $this->whereLike('country', $country);
    }

    public function archived($value = "false")
    {
        if (strtolower($value) == "true") {
            return $this->whereNotNull('archived_at');
        }
        return $this->whereNull('archived_at');
    }
}
