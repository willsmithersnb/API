<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class NbRecommendationsFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'cell_type_id', 'formula_name'
    ];

    const FILTERABLE_COLUMNS = [
        'cell_type_id' => 'sometimes|int',
        'formula_name' => 'sometimes|string',
        'sort_col' => 'sometimes|in:cell_type_id,formula_name',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function cellType($id)
    {
        return $this->where('cell_type_id', $id);
    }

    public function formulaName($formulaName)
    {
        return $this->whereLike('formula_name', '%' . $formulaName . '%');
    }
}
