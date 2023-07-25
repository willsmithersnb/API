<?php

namespace App\Models;

use App\Scopes\CustomerFilteredScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    protected $table = 'carts';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = array('customer_id', 'name', 'custom_components', 'user_id', 'image_url');
    protected $visible = array('id', 'customer_id', 'name', 'custom_components', 'user_id', 'image_url', 'created_at', 'updated_at');

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function itemList()
    {
        return $this->morphOne('App\Models\ItemList', 'item_listable');
    }

    public function favorite()
    {
        return $this->morphOne('App\Models\Favorite', 'favoriteable');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CustomerFilteredScope);
    }
}
