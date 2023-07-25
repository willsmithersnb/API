<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pod extends Model
{
    use Filterable;
    use SoftDeletes;

    protected $table = 'pods';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('pod_serial', 'order_id', 'item_packaging_option_id', 'customer_id');
    protected $visible = array('id', 'pod_serial', 'order_id', 'item_packaging_option_id', 'customer_id', 'created_at', 'updated_at');

    public function itemPackagingOption()
    {
        return $this->belongsTo('App\Models\ItemPackagingOption');
    }
}
