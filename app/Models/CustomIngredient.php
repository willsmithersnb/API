<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomIngredient extends Model
{
    protected $table = 'custom_ingredients';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'manufacturer', 'catalog_no', 'quantity', 'quantity_unit', 'unit_type', 'customer_id', 'formula_id', 'cas_no', 'price', 'cost');
    protected $visible = array('id', 'name', 'manufacturer', 'catalog_no', 'quantity', 'quantity_unit', 'unit_type', 'customer_id', 'formula_id', 'cas_no', 'price', 'cost', 'created_at', 'updated_at');

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function formula()
    {
        return $this->belongsTo('App\Models\Formula');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
