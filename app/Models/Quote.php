<?php

namespace App\Models;

use EloquentFilter\Filterable;
use App\Scopes\CustomerFilteredScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    protected $table = 'quotes';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('customer_id', 'name', 'custom_components', 'user_id', 'billing_address_id', 'shipping_address_id', 'image_url', 'is_orderable', 'expires_at', 'price_visible_to_customer');
    protected $visible = array('id', 'customer_id', 'name', 'custom_components', 'user_id', 'billing_address_id', 'shipping_address_id', 'image_url', 'is_orderable', 'expires_at', 'price_visible_to_customer', 'created_at', 'updated_at');

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function billingAddress()
    {
        return $this->belongsTo('App\Models\Address', 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo('App\Models\Address', 'shipping_address_id');
    }

    public function itemList()
    {
        return $this->morphOne('App\Models\ItemList', 'item_listable');
    }

    public function favorite()
    {
        return $this->morphOne('App\Models\Favorite', 'favoriteable');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
