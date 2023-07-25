<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDocument extends Model
{
    use Filterable;
    use SoftDeletes;

    protected $table = 'product_documents';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'product_id', 'file_upload_id', 'document_url');
    protected $visible = array('id', 'name', 'product_id', 'file_upload_id', 'document_url', 'created_at', 'updated_at');

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
