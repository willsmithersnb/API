<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeIngredient extends Model
{
    protected $table = 'grade_ingredients';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('grade_id', 'ingredient_id', 'prestashop_id', 'price', 'cost', 'pricing_quantity', 'pricing_unit', 'is_active');
    protected $visible = array('grade_id', 'ingredient_id', 'price', 'cost', 'pricing_quantity', 'pricing_unit', 'is_active', 'created_at', 'updated_at');

    public function grade()
    {
        return $this->belongsTo('App\Models\Grade');
    }

    public function ingredient()
    {
        return $this->belongsTo('App\Models\Ingredient');
    }

    public function pricingLogs()
    {
        return $this->hasMany('App\Models\PricingLog');
    }

    public function formulaIngredients()
    {
        return $this->hasMany('App\Models\FormulaIngredient');
    }
}
