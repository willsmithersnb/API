<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class NbRecommendationsTransformer extends ModelTransformer
{
    protected $availableIncludes = ['cellType'];


    public function includeCellType(Model $model)
    {
        return $this->item($model->cellType, new ModelTransformer(), false);
    }
}
