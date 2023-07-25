<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CellType extends Model
{
    use Filterable;

    protected $table = 'cell_types';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name');
    protected $visible = array('id', 'name', 'created_at', 'updated_at');

    public function nbRecommendations()
    {
        return $this->hasMany('App\Models\NBAI\NbRecommendations');
    }

    public function recommendations()
    {
        return $this->hasMany('App\Models\NBAI\Recommendation');
    }
}
