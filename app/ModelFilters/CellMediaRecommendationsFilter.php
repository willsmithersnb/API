<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CellMediaRecommendationsFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [
        'recommendation' => [
            'recommendation_cell_type' => 'cellType_name'
        ],
        'cellMedia' => [
            'cellMedia_name' => 'name',
            'cellMedia_ids' => 'ids'
        ]
    ];
}
