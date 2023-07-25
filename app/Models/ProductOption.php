<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOption extends Model
{
    use HasFactory;
    use Filterable;
    use SoftDeletes;

    protected $table = 'product_options';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'product_id', 'quantity', 'fill_volume', 'price', 'cost');
    protected $visible = array('id', 'name', 'product_id', 'quantity', 'fill_volume', 'price', 'cost', 'created_at', 'updated_at');

    public function productPackagingOptions()
    {
        return $this->hasMany('App\Models\ProductPackagingOption');
    }

    public function productQcTests()
    {
        return $this->hasMany('App\Models\ProductQcTest');
    }

}
