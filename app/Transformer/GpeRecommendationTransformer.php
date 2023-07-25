<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class GpeRecommendationTransformer extends ModelTransformer
{
    protected $availableIncludes = ['recommendation', 'geneProteinExpression', 'expressionType'];


    public function includeRecommendation(Model $model)
    {
        return $this->item($model->recommendation, new ModelTransformer(), false);
    }

    public function includeGeneProteinExpression(Model $model)
    {
        return $this->item($model->geneProteinExpression, new ModelTransformer(), false);
    }

    public function includeExpressionType(Model $model)
    {
        return $this->item($model->expressionType, new ModelTransformer(), false);
    }
}
