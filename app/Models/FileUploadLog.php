<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileUploadLog extends Model
{
    protected $table = 'file_upload_logs';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = array('ip_address', 'user_id', 'file_name', 'bucket_path', 'uuid', 'extension');
    protected $visible = array('id', 'ip_address', 'user_id', 'file_name', 'bucket_path', 'uuid', 'extension');
}
