<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingAddon extends Model
{
    use Filterable;
    use SoftDeletes;

    protected $table = 'pricing_addons';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'conditional_variable', 'pricing_type', 'cost_type', 'is_customer_visible', 'is_enabled', 'is_conditional', 'powder_enabled', 'liquid_enabled');
    protected $visible = array('id', 'name', 'conditional_variable', 'pricing_type', 'cost_type', 'is_customer_visible', 'is_enabled', 'is_conditional', 'powder_enabled', 'liquid_enabled', 'created_at', 'updated_at');

    public function pricingAddonTiers()
    {
        return $this->hasMany('App\Models\PricingAddonTier')->orderBy('condition_greater_than', 'DESC');
    }
}
