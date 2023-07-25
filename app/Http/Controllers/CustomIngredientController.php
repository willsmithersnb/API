<?php

namespace App\Http\Controllers;

use App\Models\CustomIngredient;
use App\Transformer\CustomIngredientTransformer;
use Illuminate\Http\Request;

class CustomIngredientController extends ResourceController
{
    protected $model_class = CustomIngredient::class;

    protected $url_key = 'custom_ingredient';

    protected $rule_set = [
        'quantity' => 'sometimes|integer',
        'price' => 'sometimes|integer',
        'cost' => 'sometimes|integer'
    ];

    protected function transformer()
    {
        return new CustomIngredientTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(CustomIngredient $customIngredient)
    {
        return parent::showObject($customIngredient);
    }

    public function update(Request $request, CustomIngredient $customIngredient)
    {
        return parent::updateObject($request, $customIngredient);
    }

    public function destroy(CustomIngredient $customIngredient)
    {
        return parent::destroyObject($customIngredient);
    }
}
