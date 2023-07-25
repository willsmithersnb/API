<?php

namespace App\Models\NBAI;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CriticalQualityAttribute extends Model
{
    use Filterable;
    protected $table = 'critical_quality_attributes';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name');
    protected $visible = array('id', 'name', 'created_at', 'updated_at');

    public function recommendations()
    {
        return $this->belongsTo('App\Models\NBAI\Recommendation', 'cqa_id');
    }
}
