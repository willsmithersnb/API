<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class PackagingOptionFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'name', 'price', 'cost', 'packaging_type', 'max_fill_volume', 'max_fill_volume', 'fill_tolerance', 'fill_unit', 'unit_type'
    ];

    protected $search = [
        'name', 'packaging_type'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'price' => 'sometimes|numeric',
        'cost' => 'sometimes|numeric',
        'packaging_type' => 'sometimes|string',
        'max_fill_volume' => 'sometimes|numeric',
        'fill_tolerance' => 'sometimes|numeric',
        'fill_unit' => 'sometimes|integer',
        'unit_type' => 'sometimes|integer',
        'basal_enabled' => 'sometimes|boolean',
        'balanced_salt_enabled' => 'sometimes|boolean',
        'buffer_enabled' => 'sometimes|boolean',
        'cryo_enabled' => 'sometimes|boolean',
        'sort_col' => 'sometimes|in:name,price,cost,packaging_type,has_custom_value,max_fill_volume,pricing_unit,pricing_unit_type',
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

    public function price($price)
    {
        return $this->where('price', $price);
    }

    public function cost($cost)
    {
        return $this->where('cost', $cost);
    }

    public function packagingType($packagingType)
    {
        return $this->where('packaging_type', 'ILIKE', '%' . $packagingType . '%');
    }

    public function maxFillVolume($maxFillVolume)
    {
        return $this->where('max_fill_volume', $maxFillVolume);
    }

    public function fillTolerance($fillTolerance)
    {
        return $this->where('fill_tolerance', $fillTolerance);
    }

    public function fillUnit($fill_unit)
    {
        return $this->where('fill_unit', $fill_unit);
    }

    public function unitType($unit_type)
    {
        return $this->where('unit_type', $unit_type);
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

    public function isCustomerSpecific($is_customer_specific = "false")
    {
        if(strtolower($is_customer_specific) == "true"){
            return $this->whereNotNull('customer_id');
        }else{
            return $this->whereNull('customer_id');
        }
    }
}
