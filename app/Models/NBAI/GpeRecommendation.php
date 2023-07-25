<?php

namespace App\Models\NBAI;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GpeRecommendation extends Model
{
    use Filterable;
    protected $table = 'gpe_recommendations';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('recommendation_id', 'gene_protein_expression_id', 'expression_type_id');
    protected $visible = array('recommendation_id', 'gene_protein_expression_id', 'expression_type_id', 'created_at', 'updated_at');


    public function expressionType()
    {
        return $this->belongsTo('App\Models\NBAI\ExpressionType');
    }

    public function recommendation()
    {
        return $this->belongsTo('App\Models\NBAI\Recommendation');
    }

    public function geneProteinExpression()
    {
        return $this->belongsTo('App\Models\NBAI\GeneProteinExpression');
    }
}
