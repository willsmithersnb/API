<?php

namespace App\Models\NBAI;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class NbRecommendations extends Model
{
    use Filterable;

    protected $table = 'nb_recommendations';
    public $timestamps = true;
    protected $fillable = array('cell_type_id', 'formula_name');
    protected $visible = array('id', 'cell_type_id', 'formula_name', 'created_at', 'updated_at');

    public function cellType()
    {
        return $this->belongsTo('App\Models\CellType');
    }
}
