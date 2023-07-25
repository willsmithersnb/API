<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class UserInterestFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'name', 'cell_media_id', 'email', 'supplements'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'cell_media_id' => 'sometimes|integer',
        'email' => 'sometimes|string',
        'supplements' => 'sometimes|string',
        'cellMedia_name' => 'sometimes|string',
        'sort_col' => 'sometimes|in:name,cell_media_id,email,supplements,cellMedia_name',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC'
    ];


    public $relations = [
        'cellMedia' => [
            'cellMedia_name' => 'name',
        ]
    ];

    public function cellMedia($id)
    {
        return $this->where('cell_media_id', $id);
    }

    public function email($email)
    {
        return $this->where('email', $email);
    }

    public function supplements($supplements)
    {
        return $this->whereLike('supplements', '%' . $supplements . '$');
    }
}
