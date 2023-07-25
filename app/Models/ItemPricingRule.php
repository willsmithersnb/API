<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemPricingRule extends Model
{
    protected $table = 'item_pricing_rules';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('pricing_rule_id', 'item_id', 'price', 'cost', 'customer_id');
    protected $visible = array('pricing_rule_id', 'item_id', 'price', 'cost', 'customer_id', 'created_at', 'updated_at');

    public function pricingRule()
    {
        return $this->belongsTo('App\Models\PricingRule');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
