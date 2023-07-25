<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    protected $table = 'devices';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('uuid', 'model_no', 'model_name', 'device_name', 'ssh_public_key', 'password', 'manufacture_date', 'status', 'hardware_version', 'last_known_ip', 'last_seen', 'created_by');
    protected $visible = array('id', 'uuid', 'model_no', 'model_name', 'device_name', 'ssh_public_key', 'manufacture_date', 'status', 'hardware_version', 'last_known_ip', 'last_seen', 'created_by', 'created_at', 'updated_at');

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
