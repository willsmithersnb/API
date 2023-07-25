<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QcTest extends Model
{
    use Filterable;

    protected $table = 'qc_tests';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'ui_component_name', 'description', 'has_custom_value', 'basal_enabled', 'balanced_salt_enabled', 'buffer_enabled', 'cryo_enabled', 'order');
    protected $visible = array('id', 'name', 'ui_component_name', 'description', 'has_custom_value', 'basal_enabled', 'balanced_salt_enabled', 'buffer_enabled', 'cryo_enabled', 'order', 'created_at', 'updated_at');

    // Never Transform
    public function itemQcTestMethods()
    {
        return $this->hasMany('App\Models\ItemQcTestMethod');
    }

    public function qcTestMethods()
    {
        return $this->hasMany('App\Models\QcTestMethod');
    }

    public function logs()
    {
        return $this->morphMany('App\Models\ConfigurablePricingLog', 'legible');
    }
}
