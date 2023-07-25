<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
    protected $table = 'product_types';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'customizable', 'liquid_enabled', 'powder_enabled', 'cgmp_enabled', 'vial_enabled');
    protected $visible = array('id', 'name', 'customizable', 'liquid_enabled', 'powder_enabled', 'cgmp_enabled', 'vial_enabled', 'created_at', 'updated_at');

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
