<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;
use App\Traits\SearchTrait;

class FileUploadFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'created_at', 'file_name'
    ];

    protected $search = [
        'id', 'bucket_path', 'extension', 'user_id', 'created_at', 'file_name'
    ];

    public $relations = [];

    public function extension($extension)
    {
        return $this->where('extension', 'ILIKE', '%' . $extension . '%');
    }

    public function prefix($prefix)
    {
        return $this->where('bucket_path', 'ILIKE', '%' . $prefix . '%');
    }

    public function fileName($file_name)
    {
        return $this->where('file_name', 'ILIKE', '%' . $file_name . '%');
    }
}
