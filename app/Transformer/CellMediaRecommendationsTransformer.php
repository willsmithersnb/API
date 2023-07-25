<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class CellMediaRecommendationsTransformer extends ModelTransformer
{
    protected $availableIncludes = ['recommendation', 'cellMedia'];


    public function includeRecommendation(Model $model)
    {
        return $this->item($model->recommendation, new ModelTransformer(), false);
    }

    public function includeCellMedia(Model $model)
    {
        return $this->item($model->cellMedia, new ModelTransformer(), false);
    }
}
