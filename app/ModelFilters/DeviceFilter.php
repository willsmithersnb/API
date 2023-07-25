<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class DeviceFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'model_no', 'model_name', 'device_name', 'manufacture_date', 'status', 'hardware_version', 'last_seen', 'user.created_by'
    ];

    protected $search = [
        'model_no', 'model_name', 'device_name', 'status', 'hardware_version'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function modelNo($model_no)
    {
        return $this->where('model_no', 'ILIKE', '%' . $model_no . '%');
    }

    public function modelName($model_name)
    {
        return $this->where('model_name', 'ILIKE', '%' . $model_name . '%');
    }

    public function manufactureDate($manufacture_date)
    {
        return $this->where('manufacture_date',  $manufacture_date);
    }

    public function status($status)
    {
        return $this->where('status',  $status);
    }

    public function hardwareVersion($hardware_version)
    {
        return $this->where('hardware_version',  'ILIKE', '%' . $hardware_version . '%');
    }

    public function lastSeen($last_seen)
    {
        return $this->where('last_seen',  $last_seen);
    }
}
