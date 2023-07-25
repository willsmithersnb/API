<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemPackagingOption extends Model
{
    protected $table = 'item_packaging_options';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('item_id', 'packaging_option_id', 'price', 'cost', 'fill_amount', 'quantity', 'value', 'fill_unit', 'unit_type', 'fill_tolerance', 'max_fill_volume', 'customer_id');
    protected $visible = array('id', 'item_id', 'packaging_option_id', 'price', 'cost', 'fill_amount', 'quantity', 'value', 'fill_unit', 'unit_type', 'fill_tolerance', 'max_fill_volume', 'customer_id', 'created_at', 'updated_at');

    public function packagingOption()
    {
        return $this->belongsTo('App\Models\PackagingOption');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function pods()
    {
        return $this->hasMany('App\Models\Pod');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
