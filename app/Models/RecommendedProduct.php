<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class RecommendedProduct extends Model
{
    use Filterable;
    use SoftDeletes;

    protected $table = 'recommended_products';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('parent_id', 'product_id');
    protected $visible = array('id', 'parent_id', 'product_id', 'created_at'. 'updated_at');

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
