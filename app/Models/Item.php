<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    protected $table = 'items';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;
    use LogsActivity;

    protected $dates = ['deleted_at'];
    protected $fillable = array('item_list_id', 'formula_id', 'product_id', 'item_summary_id', 'item_no', 'name', 'price', 'cost', 'customer_id', 'product_type_id', 'product_option_id');
    protected $visible = array('id', 'item_list_id', 'formula_id', 'product_id', 'item_summary_id', 'item_no', 'name', 'price', 'cost', 'customer_id', 'product_type_id', 'product_option_id', 'created_at', 'updated_at');
    protected static $logAttributes = ['item_list_id', 'formula_id', 'product_id', 'item_summary_id', 'item_no', 'name', 'price', 'cost', 'customer_id', 'product_type_id', 'product_option_id'];
    protected static $logOnlyDirty = true;

    public function formula()
    {
        return $this->belongsTo('App\Models\Formula', 'formula_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function productType()
    {
        return $this->belongsTo('App\Models\ProductType', 'product_type_id');
    }

    public function itemSummary()
    {
        return $this->belongsTo('App\Models\ItemSummary');
    }

    public function itemList()
    {
        return $this->belongsTo('App\Models\ItemList');
    }

    public function itemQcTestMethod()
    {
        return $this->hasMany('App\Models\ItemQcTestMethod');
    }

    public function itemPackagingOptions()
    {
        return $this->hasMany('App\Models\ItemPackagingOption');
    }

    public function itemPricingRule()
    {
        return $this->hasOne('App\Models\ItemPricingRule');
    }

    public function itemPricingAddonTiers()
    {
        return $this->hasMany('App\Models\ItemPricingAddonTier');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function productOption()
    {
        return $this->belongsTo('App\Models\ProductOption', 'product_option_id');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
