<?php

namespace App\Http\Controllers;

use App\Models\FormulaIngredient;
use App\Transformer\FormulaIngredientTransformer;
use Illuminate\Http\Request;

class FormulaIngredientController extends ResourceController
{
    protected $model_class = FormulaIngredient::class;

    protected $url_key = 'formula_ingredient';

    protected $rule_set = [
        'quantity' => 'sometimes|integer',
        'price' => 'sometimes|integer',
        'cost' => 'sometimes|integer'
    ];

    protected function transformer()
    {
        return new FormulaIngredientTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(FormulaIngredient $formulaIngredient)
    {
        return parent::showObject($formulaIngredient);
    }

    public function update(Request $request, FormulaIngredient $formulaIngredient)
    {
        return parent::updateObject($request, $formulaIngredient);
    }

    public function destroy(FormulaIngredient $formulaIngredient)
    {
        return parent::destroyObject($formulaIngredient);
    }
}
