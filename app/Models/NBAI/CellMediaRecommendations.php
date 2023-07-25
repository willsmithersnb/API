<?php

namespace App\Models\NBAI;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CellMediaRecommendations extends Model
{
    use Filterable;

    protected $table = 'cell_media_recommendations';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('recommendation_id', 'cell_media_id');
    protected $visible = array('recommendation_id', 'cell_media_id', 'created_at', 'updated_at');

    public function recommendation()
    {
        return $this->belongsTo('App\Models\NBAI\Recommendation');
    }

    public function cellMedia()
    {
        return $this->belongsTo('App\Models\NBAI\CellMedia');
    }
}
