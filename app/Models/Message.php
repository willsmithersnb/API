<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use App\Scopes\MessageOwnedByCustomerScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use Filterable;

    protected $table = 'messages';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('message_thread_id', 'user_id', 'body', 'from_admin', 'customer_id');
    protected $visible = array('id', 'message_thread_id', 'user_id', 'body', 'from_admin', 'customer_id', 'created_at', 'updated_at');

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function messageThread()
    {
        return $this->belongsTo('App\Models\MessageThread');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment');
    }

    protected static function booted()
    {
        static::addGlobalScope(new MessageOwnedByCustomerScope);
    }
}
