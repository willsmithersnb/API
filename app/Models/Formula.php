<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use App\Scopes\NullableCustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formula extends Model
{
    protected $table = 'formulas';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('customer_id', 'parent_id', 'name', 'formula_hash');
    protected $visible = array('id', 'customer_id', 'parent_id', 'name', 'formula_hash', 'created_at', 'updated_at');

    public function formulaIngredients()
    {
        return $this->hasMany('App\Models\FormulaIngredient');
    }

    public function customIngredients()
    {
        return $this->hasMany('App\Models\CustomIngredient');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Formula', 'parent_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

    protected static function booted()
    {
        static::addGlobalScope(new NullableCustomerFilteredScope);
    }
}
