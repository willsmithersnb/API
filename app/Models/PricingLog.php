<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingLog extends Model
{
    protected $table = 'pricing_logs';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('grade_ingredient_id', 'price', 'cost', 'pricing_quantity', 'pricing_unit', 'pricing_unit_type', 'is_active');
    protected $visible = array('grade_ingredient_id', 'price', 'cost', 'pricing_quantity', 'pricing_unit', 'pricing_unit_type', 'is_active', 'created_at', 'updated_at');

    public function gradeIngredient()
    {
        return $this->belongsTo('App\Models\GradeIngredient');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
