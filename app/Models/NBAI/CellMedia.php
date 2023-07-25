<?php

namespace App\Models\NBAI;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CellMedia extends Model
{
    use Filterable;

    protected $table = 'cell_media';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['name'];
    protected $visible = ['id', 'name', 'created_at', 'updated_at'];

    public function recommendations()
    {
        return $this->belongsToMany('App\Models\NBAI\Recommendation', 'cell_media_recommendations');
    }
}
