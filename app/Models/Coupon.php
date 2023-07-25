<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use Filterable;

    protected $table = 'coupons';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at', 'expires_at', 'valid_from'];
    protected $fillable = array('name', 'max_discount', 'discount_percentage', 'expires_at', 'min_amount', 'coupon_type', 'coupon_code', 'valid_from', 'max_redemptions', 'limit_redemption_by', 'customer_id', 'valid_period', 'description');
    protected $visible = array('id', 'name', 'max_discount', 'discount_percentage', 'expires_at', 'min_amount', 'coupon_type', 'coupon_code', 'valid_from', 'max_redemptions', 'limit_redemption_by', 'is_nb_air_valid', 'is_nb_lux_valid', 'customer_id', 'valid_period', 'description', 'created_at', 'updated_at');

    protected $appends = [
        'is_nb_air_valid', 'is_nb_lux_valid'
    ];

    public function itemLists()
    {
        return $this->hasMany('App\Models\ItemList');
    }

    public function customersCoupons()
    {
        return $this->hasMany('App\Models\CustomerCoupon');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function getIsValidAttribute()
    {
        $period = CarbonPeriod::create($this->valid_from, $this->expires_at, CarbonPeriod::EXCLUDE_END_DATE);
        return $period->isStarted() && !$period->isEnded();
    }

    public function getIsNbAirValidAttribute()
    {
        return $this->coupon_type == 'nb_air' && $this->is_valid;
    }

    public function getIsNbLuxValidAttribute()
    {
        return $this->coupon_type == 'nb_lux' && $this->is_valid;
    }
}
