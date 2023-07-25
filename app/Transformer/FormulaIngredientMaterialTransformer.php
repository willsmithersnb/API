<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class FormulaIngredientMaterialTransformer extends ModelTransformer
{
    protected $availableIncludes = ['formulaIngredient', 'material'];

    public function includeFormulaIngredient(Model $model)
    {
        return $this->item($model->formulaIngredient, new FormulaIngredientTransformer(), false);
    }

    public function includeMaterial(Model $model)
    {
        return $this->item($model->material, new MaterialTransformer(), false);
    }
}
