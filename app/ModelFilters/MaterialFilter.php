<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class MaterialFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'igmp_material_id', 'igmp_spec_id', 'igmp_part_num', 'igmp_name', 'igmp_material_description', 'igmp_lead_time', 'grade', 'storage_requirement', 'reference_num', 'reference_type', 'price', 'cost', 'pricing_quantity', 'pricing_unit', 'display_unit', 'is_active', 'nb_part_num'
    ];

    protected $search = [
        'igmp_spec_id', 'igmp_part_num', 'igmp_name', 'igmp_material_description', 'grade', 'storage_requirement', 'reference_num', 'reference_type', 'nb_part_num'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function igmpMaterialId($igmp_material_id)
    {
        return $this->where('igmp_material_id', 'ILIKE', '%' . $igmp_material_id . '%');
    }

    public function igmpSpecId($igmp_spec_id)
    {
        return $this->where('igmp_spec_id' . 'ILIKE', '%' . $igmp_spec_id . '%');
    }

    public function igmpPartNum($igmp_part_num)
    {
        return $this->where('igmp_part_num', 'ILIKE', '%' . $igmp_part_num . '%');
    }

    public function igmpName($igmp_name)
    {
        return $this->where('igmp_name', 'ILIKE', '%' . $igmp_name . '%');
    }

    public function igmpMaterialDescription($igmp_material_description)
    {
        return $this->where('igmp_material_description', 'ILIKE', '%' . $igmp_material_description . '%');
    }

    public function grade($grade)
    {
        return $this->where('grade', 'ILIKE', '%' . $grade . '%');
    }

    public function storageRequirement($storage_requirement)
    {
        return $this->where('storage_requirement', 'ILIKE', '%' . $storage_requirement . '%');
    }

    public function referenceNum($reference_num)
    {
        return $this->where('reference_num', 'ILIKE', '%' . $reference_num . '%');
    }

    public function referenceType($reference_type)
    {
        return $this->where('reference_type', $reference_type);
    }

    public function price($price)
    {
        return $this->where('price', $price);
    }

    public function cost($cost)
    {
        return $this->where('cost', $cost);
    }

    public function pricingQuantity($pricing_quantity)
    {
        return $this->where('pricing_quantity', $pricing_quantity);
    }

    public function isActive($is_active)
    {
        return $this->where('is_active', $is_active);
    }

    public function nbPartNum($nb_part_num)
    {
        return $this->where('nb_part_num', 'ILIKE', '%' . $nb_part_num . '%');
    }
}
