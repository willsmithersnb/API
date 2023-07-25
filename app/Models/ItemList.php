<?php

namespace App\Models;

use EloquentFilter\Filterable;
use App\Scopes\CustomerFilteredScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemList extends Model
{
    protected $table = 'item_lists';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('coupon_id', 'item_listable_type', 'item_listable_id', 'gross_total', 'price_per_liter', 'total_units', 'total_order_size', 'lead_time', 'discount', 'discount_percentage', 'customer_id', 'image_url', 'adjustments');
    protected $visible = array('id', 'coupon_id', 'item_listable_type', 'item_listable_id', 'gross_total', 'price_per_liter', 'total_units', 'total_order_size', 'lead_time', 'discount', 'discount_percentage', 'customer_id', 'image_url', 'adjustments', 'net_total', 'is_changelogs_available', 'created_at', 'updated_at');

    protected $appends = [
        'net_total', 'is_changelogs_available'
    ];

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

    public function coupon()
    {
        return $this->belongsTo('App\Models\Coupon');
    }

    public function attachment()
    {
        return $this->morphOne('App\Models\Attachment', 'attachable');
    }

    public function item_listable()
    {
        return $this->morphTo();
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function itemListChangeLogs()
    {
        return $this->hasMany('App\Models\ItemListChangeLog');
    }

    public function getNetTotalAttribute()
    {
        $net_total = $this->gross_total + ($this->adjustments - $this->discount);
        return $net_total;
    }

    public function getIsChangeLogsAvailableAttribute()
    {
        return $this->itemListChangeLogs()->count() > 0;
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
