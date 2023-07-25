<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceFirmware extends Model
{
    protected $table = 'device_firmware';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('device_id', 'firmware_id', 'installed_at');
    protected $visible = array('id', 'device_id', 'firmware_id', 'installed_at', 'created_at', 'updated_at');

    public function device()
    {
        return $this->belongsTo('App\Models\Device');
    }

    public function firmware()
    {
        return $this->belongsTo('App\Models\Firmware');
    }
}
