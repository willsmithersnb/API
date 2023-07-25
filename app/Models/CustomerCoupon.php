<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerCoupon extends Model
{
    use Filterable;

    protected $table = 'customer_coupons';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('customer_id', 'coupon_id', 'discountable_id', 'discountable_type', 'redeemed_by');
    protected $visible = array('customer_id', 'coupon_id', 'discountable_id', 'discountable_type', 'redeemed_by', 'created_at', 'updated_at');

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function coupon()
    {
        return $this->belongsTo('App\Models\Coupon');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'redeemed_by', 'id', 'users');
    }
}
