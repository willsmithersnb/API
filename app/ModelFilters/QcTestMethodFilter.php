<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class QcTestMethodFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'name'
    ];

    protected $search = [
        'name'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'sort_col' => 'sometimes|in:name',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC',
        'qc_test_id' => 'sometimes|integer'
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

    public function qcTestId($qc_test_id)
    {
        return $this->where('qc_test_id', $qc_test_id);
    }
}
