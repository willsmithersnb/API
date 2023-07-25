<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemSummary extends Model
{
    protected $table = 'item_summaries';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('format', 'concentration', 'pH', 'cgmp_manufacturing', 'formulation_weight', 'predicted_osmolality', 'customer_id', 'total_units', 'total_order_size', 'notes', 'product_type_id', 'product_id');
    protected $visible = array('format', 'concentration', 'pH', 'cgmp_manufacturing', 'formulation_weight', 'predicted_osmolality', 'customer_id', 'total_units', 'total_order_size', 'notes', 'product_type_id', 'product_id', 'created_at', 'updated_at');

    public function item()
    {
        return $this->hasOne('App\Models\Item');
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
