<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigurablePricingLog extends Model
{
    protected $table = 'configurable_pricing_logs';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('legible', 'user_id', 'price', 'before_obj');
    protected $visible = array('legible', 'user_id', 'price', 'before_obj', 'created_at', 'updated_at');

    public function legible()
    {
        return $this->morphTo();
    }
}
