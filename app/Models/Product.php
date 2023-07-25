<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use Filterable;

    protected $table = 'products';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('formula_id', 'product_type_id', 'name', 'supplier_name', 'is_featured', 'is_displayed', 'lead_time', 'product_description', 'maximum_order_quantity', 'default_order_quantity', 'is_customizable', 'details', 'testing_details');
    protected $visible = array('id', 'formula_id', 'product_type_id', 'name', 'supplier_name', 'is_featured', 'is_displayed', 'lead_time', 'product_description', 'maximum_order_quantity', 'default_order_quantity', 'is_customizable', 'details', 'testing_details', 'created_at', 'updated_at');

    public function catalogs()
    {
        return $this->hasMany('App\Models\Catalog');
    }

    public function productType()
    {
        return $this->belongsTo('App\Models\ProductType');
    }

    public function formula()
    {
        return $this->belongsTo('App\Models\Formula');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

    public function productDocuments()
    {
        return $this->hasMany('App\Models\ProductDocument');
    }

    public function productOptions()
    {
        return $this->hasMany('App\Models\ProductOption');
    }

    public function productImageUploads()
    {
        return $this->hasMany('App\Models\ProductImageUpload');
    }

    public function recommendedProducts()
    {
        return $this->hasMany('App\Models\RecommendedProduct', 'parent_id');
    }
}
