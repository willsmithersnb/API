<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Ingredient extends Model
{
    protected $table = 'ingredients';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;
    use LogsActivity;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'prestashop_name', 'ingredient_type_id', 'molecular_mass', 'osmolality', 'min_quantity', 'max_quantity', 'reference_num', 'reference_type', 'display_unit', 'url', 'basal_enabled', 'balanced_salt_enabled', 'buffer_enabled', 'cryo_enabled', 'pricing_unit', 'unit_type', 'price', 'cost', 'podable', 'is_enabled');
    protected $visible = array('id', 'name', 'prestashop_name', 'ingredient_type_id', 'molecular_mass', 'osmolality', 'min_quantity', 'max_quantity', 'reference_num', 'reference_type', 'display_unit', 'url', 'basal_enabled', 'balanced_salt_enabled', 'buffer_enabled', 'cryo_enabled', 'pricing_unit', 'unit_type', 'price', 'cost', 'podable', 'is_enabled', 'created_at', 'updated_at');
    protected static $logAttributes = ['name', 'prestashop_name', 'ingredient_type_id', 'molecular_mass', 'osmolality', 'min_quantity', 'max_quantity', 'reference_num', 'reference_type', 'display_unit', 'url', 'basal_enabled', 'balanced_salt_enabled', 'buffer_enabled', 'cryo_enabled', 'pricing_unit', 'unit_type', 'price', 'cost', 'podable', 'is_enabled'];
    protected static $logOnlyDirty = true;

    // Never Transform
    public function formulaIngredients()
    {
        return $this->hasMany('App\Models\FormulaIngredient');
    }

    public function materials()
    {
        return $this->morphMany('App\Models\Material', 'materialable');
    }

    public function recommendations()
    {
        return $this->hasMany('App\Models\NBAI\Recommendation');
    }

    public function ingredientType()
    {
        return $this->belongsTo('App\Models\IngredientType');
    }
}
