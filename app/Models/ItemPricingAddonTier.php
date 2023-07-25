<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemPricingAddonTier extends Model
{
    use SoftDeletes;
    use Filterable;

    protected $table = 'item_pricing_addon_tiers';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('item_id','pricing_addon_id', 'pricing_addon_tier_id', 'conditional_variable', 'name', 'pricing_type', 'cost_type', 'is_customer_visible', 'is_enabled', 'condition_greater_than', 'price', 'cost');
    protected $visible = array('id', 'item_id', 'pricing_addon_id', 'pricing_addon_tier_id', 'conditional_variable', 'name', 'pricing_type', 'cost_type', 'is_customer_visible', 'is_enabled', 'condition_greater_than', 'price', 'cost', 'created_at', 'updated_at');

    public function pricingAddon()
    {
        return $this->belongsTo('App\Models\PricingAddon', 'pricing_addon_id', 'id');
    }

    public function pricingAddonTier()
    {
        return $this->belongsTo('App\Models\PricingAddonTier', 'pricing_addon_tier_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }
}
