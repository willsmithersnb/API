<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class ProductFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'formula_id', 'product_type_id', 'name', 'supplier_name', 'is_featured', 'is_displayed', 'lead_time', 'catalogs.number', 'productType.name'
    ];

    protected $search = [
        'name', 'supplier_name', 'lead_time', 'catalogs.number', 'catalogs.name'
    ];

    const FILTERABLE_COLUMNS =  [
        'formula_id' => 'sometimes|numeric',
        'product_type_id' => 'sometimes|numeric',
        'name' => 'sometimes|string',
        'supplier_name' => 'sometimes|string',
        'is_featured' => 'sometimes|boolean',
        'is_displayed' => 'sometimes|boolean',
        'lead_time' => 'sometimes|numeric',
        'sort_col' => 'sometimes|in:formula_id, product_type_id, name, supplier_name, is_featured, is_displayed, lead_time',
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

    public function supplierName($supplier_name)
    {
        return $this->where('supplier_name', 'ILIKE', '%' . $supplier_name . '%');
    }

    public function isFeatured($is_featured)
    {
        return $this->where('is_featured', $is_featured);
    }

    public function isDisplayed($is_displayed)
    {
        return $this->where('is_displayed', $is_displayed);
    }

    public function lead_time($lead_time)
    {
        return $this->where('lead_time', 'ILIKE', '%' . $lead_time . '%');
    }

    public function catalogNumber($catalog_number)
    {
        return $this->whereHas('catalogs', function ($q) use ($catalog_number) {
            $q->where('number', 'ILIKE', '%' . $catalog_number . '%');
        });
    }

    public function productTypeName($product_type_name)
    {
        return $this->whereHas('productType', function ($q) use ($product_type_name) {
            $q->where('name', 'ILIKE', '%' . $product_type_name . '%');
        });
    }
}
