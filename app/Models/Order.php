<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    protected $table = 'orders';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('customer_id', 'name', 'user_id', 'delivery_date', 'billing_address_id', 'shipping_address_id', 'order_status_id', 'payment_status_id', 'payment_type_id', 'image_url', 'order_signal', 'is_automatic');
    protected $visible = array('id', 'customer_id', 'name', 'user_id', 'created_at', 'updated_at', 'delivery_date', 'billing_address_id', 'shipping_address_id', 'order_status_id', 'payment_status_id', 'payment_type_id', 'image_url', 'order_signal', 'is_automatic', 'created_at'. 'updated_at');

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

    public function documents()
    {
        return $this->hasMany('App\Models\Documentation');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
        static::retrieved(function ($model) {
            if (($model->is_automatic === TRUE) && (now() > $model->delivery_date)) {
                $model->order_signal = 'delayed';
                $model->save();
            }
        });
    }
}
