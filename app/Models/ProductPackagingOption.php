<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPackagingOption extends Model
{
    use Filterable;
    use SoftDeletes;

    protected $table = 'product_packaging_options';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('product_id', 'packaging_option_id');
    protected $visible = array('id', 'product_option_id', 'packaging_option_id', 'created_at', 'updated_at');

    public function productOption()
    {
        return $this->belongsTo('App\Models\ProductOption');
    }

    public function packagingOption()
    {
        return $this->belongsTo('App\Models\PackagingOption');
    }
}
