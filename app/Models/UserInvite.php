<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInvite extends Model
{
    protected $table = 'user_invites';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];

    protected $fillable = array('invited_by', 'email', 'first_name', 'last_name', 'expires_at', 'status', 'customer_id', 'permissions', 'accepted_at');
    protected $visible = array('id', 'invited_by', 'email', 'first_name', 'last_name', 'expires_at', 'status', 'customer_id', 'permissions', 'accepted_at', 'created_at', 'updated_at');


    public function invitedBy()
    {
        return $this->belongsTo('App\User', 'invited_by');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
