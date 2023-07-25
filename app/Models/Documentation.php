<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documentation extends Model
{
    protected $table = 'documentations';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'order_id', 'file_upload_id', 'customer_id');
    protected $visible = array('id', 'name', 'order_id', 'file_upload_id', 'customer_id', 'created_at', 'updated_at');

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function fileUpload()
    {
        return $this->belongsTo('App\Models\FileUpload');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
