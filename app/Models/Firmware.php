<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Firmware extends Model
{
    protected $table = 'firmware';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('version', 'expressions', 'notes', 'uploaded_by', 'file_upload_id');
    protected $visible = array('id', 'version', 'expressions', 'notes', 'uploaded_by', 'file_upload_id', 'created_at', 'updated_at');

    public function user()
    {
        return $this->belongsTo('App\User', 'uploaded_by');
    }

    public function fileUpload()
    {
        return $this->belongsTo('App\Models\FileUpload');
    }
}
