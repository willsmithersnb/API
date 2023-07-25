<?php

namespace App\Models\NBAI;


use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpressionType extends Model
{
    use Filterable;

    protected $table = 'expression_types';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name');
    protected $visible = array('id', 'name', 'created_at', 'updated_at');

    public function geneProteinExpression()
    {
        return $this->hasMany('App\Models\NBAI\GeneProteinExpression');
    }
}
