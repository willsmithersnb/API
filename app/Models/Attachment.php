<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    protected $table = 'attachments';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('attachable_type', 'attachable_id', 'message_id', 'name', 'customer_id');
    protected $visible = array('attachable_type', 'attachable_id', 'message_id', 'customer_id', 'name', 'created_at', 'updated_at');

    public function message()
    {
        return $this->belongsTo('App\Models\Message');
    }

    public function attachable()
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
