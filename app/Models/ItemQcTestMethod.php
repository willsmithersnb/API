<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemQcTestMethod extends Model
{
    protected $table = 'item_qc_test_methods';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('item_id', 'qc_test_id', 'qc_test_method_id', 'price', 'cost', 'value', 'customer_id');
    protected $visible = array('id', 'item_id', 'qc_test_id', 'qc_test_method_id', 'price', 'cost', 'value', 'customer_id', 'created_at', 'updated_at');

    public function qcTest()
    {
        return $this->belongsTo('App\Models\QcTest', 'qc_test_id');
    }

    public function qcTestMethod()
    {
        return $this->belongsTo('App\Models\QcTestMethod', 'qc_test_method_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
