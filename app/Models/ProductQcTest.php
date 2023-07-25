<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductQcTest extends Model
{
    use Filterable;
    use SoftDeletes;

    protected $table = 'product_qc_tests';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('product_id', 'qc_test_id', 'qc_test_method_id', 'value');
    protected $visible = array('id', 'product_id', 'qc_test_id', 'qc_test_method_id', 'value', 'created_at', 'updated_at');

    public function productOption()
    {
        return $this->belongsTo('App\Models\ProductOption');
    }

    public function qcTest()
    {
        return $this->belongsTo('App\Models\QcTest', 'qc_test_id');
    }

    public function qcTestMethod()
    {
        return $this->belongsTo('App\Models\QcTestMethod', 'qc_test_method_id');
    }
}
