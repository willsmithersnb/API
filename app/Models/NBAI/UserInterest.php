<?php

namespace App\Models\NBAI;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInterest extends Model
{
    use Filterable;

    protected $table = 'user_interests';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('supplements', 'cell_media_id', 'email');
    protected $visible = array('id', 'supplements', 'cell_media_id', 'email', 'created_at', 'updated_at');

    public function cellMedia(): BelongsTo
    {
        return $this->belongsTo('App\Models\NBAI\CellMedia');
    }
}
