<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class JournalFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'name', 'impact_factor'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'impact_factor' => 'sometimes|numeric',
        'sort_col' => 'sometimes|in:name,impact_factor',
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

    public function impactFactor($impactFactor)
    {
        return $this->where('impact_factor', $impactFactor);
    }
}
