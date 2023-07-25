<?php

namespace App\Models;

use App\Scopes\NullableCustomerFilteredScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormulaIngredientMaterial extends Model
{
    protected $table = 'formula_ingredient_materials';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('material_id', 'formula_ingredient_id', 'unit_type', 'pricing_unit', 'price', 'cost', 'customer_id');
    protected $visible = array('id', 'material_id', 'formula_ingredient_id', 'unit_type', 'pricing_unit', 'price', 'cost', 'customer_id', 'created_at', 'updated_at');

    public function formulaIngredient()
    {
        return $this->belongsTo('App\Models\FormulaIngredient');
    }

    public function material()
    {
        return $this->belongsTo('App\Models\Material');
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
