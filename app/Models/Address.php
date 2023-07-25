<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use Filterable;

    protected $table = 'addresses';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('customer_id', 'line_1', 'line_2', 'city', 'state', 'zip_code', 'country', 'archived_at', 'object_hash');
    protected $visible = array('id', 'customer_id', 'line_1', 'line_2', 'city', 'state', 'zip_code', 'country', 'archived_at', 'object_hash', 'created_at', 'updated_at');

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function billingOrders()
    {
        return $this->hasMany('App\Models\Order', 'billing_address_id');
    }

    public function shippingOrders()
    {
        return $this->hasMany('App\Models\Order', 'shipping_address_id');
    }

    public function billingQuotes()
    {
        return $this->hasMany('App\Models\Quote', 'billing_address_id');
    }

    public function shippingQuotes()
    {
        return $this->hasMany('App\Models\Quote', 'shipping_address_id');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
