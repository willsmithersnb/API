<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;

class CouponFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'name', 'max_discount', 'discount_percentage', 'expires_at', 'created_at'
    ];

    protected $search = [
        'name', 'coupon_code'
    ];

    const FILTERABLE_COLUMNS = [
        'name' => 'sometimes|string',
        'max_discount' => 'sometimes|numeric',
        'discount_percentage' => 'sometimes|numeric',
        'sort_col' => 'sometimes|in:name,max_discount,discount_percentage',
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
        return $this->whereLike('name', $name);
    }

    public function maxDiscount($max_discount)
    {
        return $this->whereLike('max_discount', $max_discount);
    }

    public function discountPercentage($discount_percentage)
    {
        return $this->whereLike('discount_percentage', $discount_percentage);
    }

    public function expiresAt($expires_at)
    {
        return $this->whereLike('expires_at', $expires_at);
    }

    public function isCustomerSpecific($is_customer_specific = "false")
    {
        if(strtolower($is_customer_specific) == "true"){
            return $this->whereNotNull('customer_id');
        }else{
            return $this->whereNull('customer_id');
        }
    }

    public function customerId($customer_id)
    {
        return $this->where('customer_id', $customer_id);
    }

    public function couponType($couponType)
    {
        return $this->where('coupon_type', $couponType);
    }

    public function maxRedemptionsReached($max_redemptions_reached = "false")
    {
        $couponIds = $this->getModel()->get()->map(function ($coupon) use ($max_redemptions_reached) {
            if (strtolower($max_redemptions_reached) == "true") {
                if ((($coupon->customersCoupons->count()) >= $coupon->max_redemptions)) {
                    return $coupon->id;
                }
            } else {
                if ((($coupon->customersCoupons->count()) < $coupon->max_redemptions)) {
                    return $coupon->id;
                }
            }
            return null;
        })->toArray();

        return $this->whereIn('id', $couponIds);
    }
}
