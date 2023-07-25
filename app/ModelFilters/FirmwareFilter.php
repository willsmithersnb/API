<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class FirmwareFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'version', 'expressions', 'notes', 'uploaded_by', 'created_at'
    ];

    protected $search = [
        'version', 'expressions', 'notes'
    ];


    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function notes($notes)
    {
        return $this->where('notes', 'ILIKE', '%' . $notes . '%');
    }

    public function version($version)
    {
        return $this->version('version', 'ILIKE', '%' . $version . '%');
    }

    public function expressions($expressions)
    {
        return $this->version('expressions', 'ILIKE', '%' . $expressions . '%');
    }

    public function uploadedBy($uploaded_by)
    {
        return $this->where('uploaded_by',  $uploaded_by);
    }
}
