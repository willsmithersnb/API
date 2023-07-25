<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'favorites';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'favoriteable_type', 'favoriteable_id', 'customer_id');
    protected $visible = array('id', 'name', 'favoriteable_type', 'favoriteable_id', 'customer_id', 'created_at', 'updated_at');

    public function favoriteable()
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
