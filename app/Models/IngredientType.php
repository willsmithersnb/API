<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IngredientType extends Model
{
    protected $table = 'ingredient_types';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'order');
    protected $visible = array('id', 'name', 'order', 'created_at', 'updated_at');

    public function ingredients()
    {
        return $this->hasMany('App\Models\Ingredient');
    }
}
