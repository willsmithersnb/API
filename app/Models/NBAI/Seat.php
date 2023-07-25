<?php

namespace App\Models\NBAI;

use App\Scopes\CustomerFilteredScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seat extends Model
{
    use Filterable;

    protected $table = 'seats';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at', 'expires_at'];
    protected $fillable = ['user_id', 'uuid', 'expires_at', 'status', 'payment_type', 'customer_id'];
    protected $visible = ['id', 'user_id', 'uuid', 'expires_at', 'status', 'payment_type', 'customer_id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope('customer_id'));
    }
}
