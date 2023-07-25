<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\SelectTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class IngredientFilter extends ModelFilter
{
    use Sortable;
    use SelectTrait;
    use SearchTrait;

    protected $sortableColumns = [
        "id", "name", "molecular_mass", "osmolality", "min_quantity", "max_quantity", "reference_num", "reference_type", "display_unit", "unit_type", "url", "basal_enabled", "balanced_salt_enabled", "buffer_enabled", "cryo_enabled", "prestashop_name", 'price', 'cost', 'pricing_unit'
    ];

    protected $search = [
        "name", "molecular_mass", "osmolality", "reference_num", "reference_type", "unit_type", "url"
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

    public function ingredientType($ingredient_type)
    {
        return $this->where('ingredient_type', 'ILIKE', '%' . $ingredient_type . '%');
    }

    public function molecularMass($molecular_mass)
    {
        return $this->where('molecular_mass',  $molecular_mass);
    }

    public function osmolality($osmolality)
    {
        return $this->where('osmolality',  $osmolality);
    }

    public function minQuantity($min_quantity)
    {
        return $this->where('min_quantity',  $min_quantity);
    }

    public function maxQuantity($max_quantity)
    {
        return $this->where('max_quantity',  $max_quantity);
    }


    public function referenceNum($reference_num)
    {
        return $this->where('reference_num', 'ILIKE', '%' . $reference_num . '%');
    }

    public function referenceType($reference_type)
    {
        return $this->where('reference_type',  $reference_type);
    }

    public function displayUnit($display_unit)
    {
        return $this->where('display_unit',  $display_unit);
    }

    public function unitType($unit_type)
    {
        return $this->where('unit_type',  $unit_type);
    }

    public function url($url)
    {
        return $this->where('url', 'ILIKE', '%' . $url . '%');
    }

    public function prestashopName($prestashop_name)
    {
        return $this->where('prestashop_name', 'ILIKE', '%' . $prestashop_name . '%');
    }

    public function isEnabled($is_enabled)
    {
        return $this->where('is_enabled',  $is_enabled);
    }
}
