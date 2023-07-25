<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class PricingAddonTier extends Model
{
    use Filterable;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'pricing_addon_tiers';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('pricing_addon_id', 'condition_greater_than', 'price', 'cost');
    protected $visible = array('id', 'pricing_addon_id', 'condition_greater_than', 'price', 'cost', 'created_at', 'updated_at');
    protected static $logAttributes = ['pricing_addon_id', 'condition_greater_than', 'price', 'cost'];
    protected static $logOnlyDirty = true;

    public function pricingAddon()
    {
        return $this->belongsTo('App\Models\PricingAddonTier', 'pricing_addon_id');
    }
}
