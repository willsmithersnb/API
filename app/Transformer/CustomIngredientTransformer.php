<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;


class CustomIngredientTransformer extends ModelTransformer
{
    protected $availableIncludes = ['formula'];

    public function includeFormula(Model $model)
    {
        return $this->item($model->formula, new FormulaTransformer(), false);
    }
}
