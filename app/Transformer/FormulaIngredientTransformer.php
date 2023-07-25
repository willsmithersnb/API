<?php

namespace App\Transformer;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Model;

class FormulaIngredientTransformer extends ModelTransformer
{
    protected $availableIncludes = ['formulaIngredientMaterials', 'formula', 'ingredient'];

    public function includeFormula(Model $model)
    {
        return $this->item($model->formula, new FormulaTransformer(), false);
    }

    public function includeFormulaIngredientMaterials(Model $model)
    {
        return $this->collection($model->formulaIngredientMaterials, new FormulaIngredientMaterialTransformer(), false);
    }

    public function includeIngredient(Model $model)
    {
        return $this->item($model->ingredient, new IngredientTransformer(), false);
    }
}
