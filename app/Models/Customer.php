<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    protected $table = 'customers';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'customer_type', 'prestashop_id');
    protected $visible = array('id', 'name', 'customer_type', 'prestashop_id', 'created_at', 'updated_at');

    public function formulas()
    {
        return $this->hasMany('App\Models\Formula');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function messageThreads()
    {
        return $this->hasMany('App\Models\MessageThread');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function carts()
    {
        return $this->hasMany('App\Models\Cart');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function quotes()
    {
        return $this->hasMany('App\Models\Quote');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

    public function itemSummaries()
    {
        return $this->hasMany('App\Models\ItemSummary');
    }

    public function itemPackagingOptions()
    {
        return $this->hasMany('App\Models\ItemPackagingOption');
    }

    public function itemPricingRules()
    {
        return $this->hasMany('App\Models\ItemPricingRule');
    }

    public function itemLists()
    {
        return $this->hasMany('App\Models\ItemList');
    }

    public function coupons()
    {
        return $this->hasMany('App\Models\Coupon');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope('id'));
    }
}
