<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class QcTestMethod extends Model
{
    use Filterable;

    protected $table = 'qc_test_methods';
    public $timestamps = true;

    use SoftDeletes;
    use LogsActivity;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'price', 'cost', 'qc_test_id', 'enabled');
    protected $visible = array('id', 'name', 'price', 'cost', 'qc_test_id', 'enabled', 'created_at', 'updated_at');
    protected static $logAttributes = ['name', 'price', 'cost', 'qc_test_id', 'enabled'];
    protected static $logOnlyDirty = true;

    public function qcTests()
    {
        return $this->belongsTo('App\Models\QcTest', 'qc_test_id');
    }

    // Never Transform
    public function itemQcTestMethods()
    {
        return $this->hasMany('App\Models\ItemQcTestMethod', 'qc_test_method_id');
    }
}
