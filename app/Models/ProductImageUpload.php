<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImageUpload extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_image_uploads';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('product_id', 'file_upload_id', 'image_url');
    protected $visible = array('id', 'product_id', 'file_upload_id', 'image_url', 'created_at', 'updated_at');

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function fileUpload()
    {
        return $this->belongsTo('App\Models\FileUpload');
    }

}
