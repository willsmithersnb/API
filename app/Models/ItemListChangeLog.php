<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemListChangeLog extends Model
{
    use Filterable;
    use SoftDeletes;

    protected $table = 'item_listable_change_logs';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('change_type', 'status_name', 'user_id', 'item_list_id', 'description', 'order_signal', 'is_automatic', 'is_visible_to_customer', 'is_email_triggered');
    protected $visible = array('id', 'change_type', 'status_name', 'user_id', 'item_list_id', 'description', 'order_signal', 'is_automatic', 'is_visible_to_customer', 'is_email_triggered', 'created_at', 'updated_at');

    public function itemList()
    {
        return $this->belongsTo('App\Models\ItemList');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
