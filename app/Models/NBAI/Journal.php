<?php

namespace App\Models\NBAI;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use Filterable;

    protected $table = 'journals';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'impact_factor');
    protected $visible = array('id', 'name', 'impact_factor', 'created_at', 'updated_at');

    public function researchPapers()
    {
        return $this->hasMany('App\Models\NBAI\ResearchPaper');
    }
}
