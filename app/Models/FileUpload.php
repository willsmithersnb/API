<?php

namespace App\Models;

use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Model
{
    use Filterable;

    protected $table = 'file_uploads';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('bucket_path', 'uuid', 'extension', 'file_name', 'public_url');
    protected $visible = array('id', 'bucket_path', 'uuid', 'extension', 'file_name', 'public_url', 'file_source_url', 'created_at', 'updated_at');
    protected $appends = [
        'file_source_url'
    ];

    public function attachment()
    {
        return $this->morphOne('App\Models\Attachment', 'attachable');
    }

    public function getFileSourceUrlAttribute()
    {
        if (is_null($this->public_url)) {
            return Storage::disk('s3')->temporaryUrl(
                $this->bucket_path . $this->file_name,
                Carbon::now()->addMinutes(config('app.presigned_url_expiry_time'))
            );
        } else {
            return $this->public_url;
        }
    }
    public function makePublic()
    {
        if ($this->id == null)
            throw new \Exception("Error Processing Request, cant toggle an unsaved file public", 1);
        if (is_null($this->public_url)) {
            Storage::disk('s3')->setVisibility(
                $this->bucket_path . $this->file_name,
                'public'
            );
            $this->public_url = Storage::disk('s3')->url($this->bucket_path . $this->file_name);
            return $this->save();
        } else {
            return $this;
        }
    }

    public function makePrivate()
    {
        if ($this->id == null)
            throw new \Exception("Error Processing Request, cant toggle an unsaved file public", 1);
        $this->public_url = null;
        Storage::disk('s3')->setVisibility(
            $this->bucket_path . $this->file_name,
            'private'
        );
        $this->save();
    }
}
