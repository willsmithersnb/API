<?php

namespace App\Transformer;

use Illuminate\Support\Facades\Auth;

class IngredientTypeTransformer extends ModelTransformer
{
    protected $availableIncludes = ['ingredients'];

    public function includeIngredients($model)
    {
        return $this->collection($model->ingredients, new IngredientTransformer(), false);
    }
}
