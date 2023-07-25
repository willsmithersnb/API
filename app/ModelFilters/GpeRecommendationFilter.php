<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class GpeRecommendationFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'name'
    ];

    const FILTERABLE_COLUMNS = [
        'sort_col' => 'sometimes|in:name',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [
        'recommendation' => [
            'recommendation_cell_type' => 'cellType_name'
        ]
    ];
}
