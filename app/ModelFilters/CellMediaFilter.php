<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Facades\Auth;

class CellMediaFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'name'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'sort_col' => 'sometimes|in:name',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    protected function setup()
    {
        if (!Auth::check()) {
            $this->blacklist = [
                'name',
                'ids'
            ];

            $this->sortableColumns = [];
        }
    }


    public function name($name)
    {
        return $this->whereLike('name', '%' . $name . '%');
    }

    public function ids($ids)
    {
        $id_list = json_decode($ids);
        return $this->whereIn('id', $id_list);
    }
}
