<?php

namespace App;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyUserNotification;
use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable;
    use Filterable;
    use HasRoles;
    use HasPermissions;
    use SoftDeletes;

    /**
     * Set Spatie guard;
     */
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'first_name', 'last_name', 'department', 'field_of_work', 'job_title', 'cell_type_interests', 'profile_picture', 'company_name', 'prestashop_id', 'customer_id'
    ];

    protected $visible = [
        'id', 'name', 'email', 'first_name', 'last_name', 'department', 'field_of_work', 'job_title', 'cell_type_interests', 'profile_picture', 'company_name', 'prestashop_id', 'customer_id', 'email_verified_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification() {
        $this->notify(new VerifyUserNotification());
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function seat()
    {
        return $this->hasOne('App\Models\NBAI\Seat');
    }

    public function isAdmin()
    {
        return $this->hasRole(['admin', 'super-admin']);
    }

    public function hasSameCustomer(Model $model)
    {
        return $model->customer_id === $this->customer_id;
    }

    public function itemListChangeLogs()
    {
        return $this->hasMany('App\Models\ItemListChangeLog');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }

    public function scopeAdmin($query)
    {
        return $query->whereHas('roles', function($q) {
            return $q->whereIn('name', ['admin', 'super-admin']);
        });
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function delete()
    {
        $this->email = $this->email . "_" . md5(Str::uuid());
        $this->save();
        return parent::delete();
    }

    public function userInvites()
    {
        return $this->hasMany('App\Models\UserInvite', 'invited_by');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = config('app.lux_url') . '/reset-password/' . $this->email . '/' . $token;
        $this->notify(new ResetPasswordNotification($url));
    }
}
