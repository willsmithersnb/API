<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use Filterable;

    protected $table = 'grades';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'display_name');
    protected $visible = array('id', 'name', 'display_name', 'created_at', 'updated_at');

    public function gradeIngredients()
    {
        return $this->hasMany('App\Models\GradeIngredient');
    }
}
