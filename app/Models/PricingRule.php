<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingRule extends Model
{
    use Filterable;

    protected $table = 'pricing_rules';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'price', 'cost', 'condition', 'has_custom_price');
    protected $visible = array('id', 'name', 'price', 'cost', 'condition', 'has_custom_price', 'created_at', 'updated_at');

    public function itemPricingRule()
    {
        return $this->hasMany('App\Models\ItemPricingRule');
    }
}
