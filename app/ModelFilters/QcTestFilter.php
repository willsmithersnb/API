<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class QcTestFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'name', 'ui_component_name', 'description', 'basal_enabled', 'balanced_salt_enabled', 'buffer_enabled', 'cryo_enabled', 'has_custom_value', 'order'
    ];

    protected $search = [
        'name', 'ui_component_name', 'description'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'ui_component_name' => 'sometimes|string',
        'description' => 'sometimes|string',
        'basal_enabled' => 'sometimes|boolean',
        'balanced_salt_enabled' => 'sometimes|boolean',
        'buffer_enabled' => 'sometimes|boolean',
        'cryo_enabled' => 'sometimes|boolean',
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

    public function name($name)
    {
        return $this->where('name', 'ILIKE', '%' . $name . '%');
    }

    public function uiComponentName($ui_component_name)
    {
        return $this->where('ui_component_name', 'ILIKE', '%' . $ui_component_name . '%');
    }

    public function description($description)
    {
        return $this->where('description', 'ILIKE', '%' . $description . '%');
    }

    public function basalEnabled($basal_enabled)
    {
        return $this->where('basal_enabled', $basal_enabled);
    }

    public function balancedSaltEnabled($balanced_salt_enabled)
    {
        return $this->where('balanced_salt_enabled', $balanced_salt_enabled);
    }

    public function bufferEnabled($buffer_enabled)
    {
        return $this->where('buffer_enabled', $buffer_enabled);
    }

    public function cryoEnabled($cryo_enabled)
    {
        return $this->where('cryo_enabled', $cryo_enabled);
    }

    public function hasCustomValue($has_custom_value)
    {
        return $this->where('has_custom_value', $has_custom_value);
    }
}
