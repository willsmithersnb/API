<?php

namespace App\Models;

use App\Scopes\NullableCustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class PackagingOption extends Model
{
    use Filterable;
    use LogsActivity;

    protected $table = 'packaging_options';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'price', 'cost', 'packaging_type', 'max_fill_volume', 'configuration', 'fill_tolerance', 'fill_unit', 'unit_type', 'packaging_hash', 'basal_enabled', 'balanced_salt_enabled', 'buffer_enabled', 'cryo_enabled', 'min_lead_time', 'max_lead_time', 'min_cgmp_lead_time', 'max_cgmp_lead_time', 'fill_cost_per_litre', 'fill_price_per_litre', 'customer_id', 'moq', 'object_hash');
    protected $visible = array('id', 'name', 'price', 'cost', 'packaging_type', 'max_fill_volume', 'configuration', 'fill_tolerance', 'fill_unit', 'unit_type', 'packaging_hash', 'created_at', 'updated_at', 'basal_enabled', 'balanced_salt_enabled', 'buffer_enabled', 'cryo_enabled', 'min_lead_time', 'max_lead_time', 'min_cgmp_lead_time', 'max_cgmp_lead_time', 'fill_cost_per_litre', 'fill_price_per_litre', 'customer_id', 'moq', 'object_hash');
    public static $hashable = array('packaging_type', 'max_fill_volume', 'fill_unit', 'unit_type');
    protected  static $logAttributes = ['name', 'price', 'cost', 'packaging_type', 'max_fill_volume', 'configuration', 'fill_tolerance', 'fill_unit', 'unit_type', 'packaging_hash', 'basal_enabled', 'balanced_salt_enabled', 'buffer_enabled', 'cryo_enabled', 'min_lead_time', 'max_lead_time', 'min_cgmp_lead_time', 'max_cgmp_lead_time', 'fill_cost_per_litre', 'fill_price_per_litre'];
    protected static $logOnlyDirty = true;

    public function itemPackagingOptions()
    {
        return $this->hasMany('App\Models\ItemPackagingOption');
    }

    public function logs()
    {
        return $this->morphMany('App\Models\ConfigurablePricingLog', 'legible');
    }

    public function materials()
    {
        return $this->morphMany('App\Models\Material', 'materialable');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    protected static function booted()
    {
        static::addGlobalScope(new NullableCustomerFilteredScope);
    }
}
