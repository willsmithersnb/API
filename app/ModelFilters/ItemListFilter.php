<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class ItemListFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'discount', 'net_total', 'discount_percentage'
    ];

    protected $search = [
        'discount', 'net_total', 'discount_percentage'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function netTotal($net_total)
    {
        return $this->where('net_total', $net_total);
    }

    public function discount($discount)
    {
        return $this->where('discount', $discount);
    }

    public function discountPercentage($discount_percentage)
    {
        return $this->where('discount_percentage', $discount_percentage);
    }

    public function couponId($coupon_id)
    {
        return $this->where('coupon_id', $coupon_id);
    }
}
