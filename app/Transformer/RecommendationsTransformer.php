<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class RecommendationsTransformer extends ModelTransformer
{
    protected $availableIncludes = ['cellType', 'criticalQualityAttributes', 'ingredient', 'researchPaper'];


    public function includeCriticalQualityAttributes(Model $model)
    {
        return $this->item($model->criticalQualityAttributes, new ModelTransformer(), false);
    }

    public function includeCellType(Model $model)
    {
        return $this->item($model->cellType, new ModelTransformer(), false);
    }

    public function includeIngredient(Model $model)
    {
        return $this->item($model->ingredient, new IngredientTransformer(), false);
    }

    public function includeResearchPaper(Model $model)
    {
        return $this->item($model->researchPaper, new ModelTransformer(), false);
    }
}
