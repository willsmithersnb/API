<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalog extends Model
{
    protected $table = 'catalogs';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('number', 'product_id');
    protected $visible = array('id', 'number', 'product_id', 'created_at', 'updated_at');

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
