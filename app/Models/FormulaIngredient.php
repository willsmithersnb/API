<?php

namespace App\Models;

use App\Scopes\NullableCustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class FormulaIngredient extends Model
{
    protected $table = 'formula_ingredients';
    public $timestamps = true;

    use SoftDeletes;
    use LogsActivity;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('formula_id', 'quantity', 'quantity_unit', 'pricing_unit', 'unit_type', 'price', 'cost', 'customer_id', 'ingredient_id');
    protected $visible = array('id', 'formula_id', 'quantity', 'quantity_unit', 'pricing_unit', 'unit_type', 'price', 'cost', 'customer_id', 'ingredient_id', 'created_at', 'updated_at');
    protected static $logAttributes = ['formula_id', 'quantity', 'quantity_unit', 'pricing_unit', 'unit_type', 'price', 'cost', 'customer_id', 'ingredient_id'];
    protected static $logOnlyDirty = true;

    public function formulaIngredientMaterials()
    {
        return $this->hasMany('App\Models\FormulaIngredientMaterial');
    }

    public function ingredient()
    {
        return $this->belongsTo('App\Models\Ingredient', 'ingredient_id');
    }
    public function formula()
    {
        return $this->belongsTo('App\Models\Formula');
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
