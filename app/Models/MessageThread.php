<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageThread extends Model
{
    use Filterable;

    protected $table = 'message_threads';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('customer_id', 'subject');
    protected $visible = array('id', 'customer_id', 'created_at', 'updated_at', 'subject');

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function lastMessage()
    {
        return $this->messages();
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope('customer_id'));
    }
}
