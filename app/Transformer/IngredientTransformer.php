<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IngredientTransformer extends ModelTransformer
{
    protected $availableIncludes = ['materials', 'ingredientType'];

    function __construct()
    {
        if (!Auth::hasUser() || !auth()->user()->isAdmin()) {
            $this->hiddenFields = ['cost'];
        }
    }

    public function includeMaterials(Model $model)
    {
        return $this->collection($model->materials, new MaterialTransformer(), false);
    }

    public function includeIngredientType(Model $model)
    {
        return $this->item($model->ingredientType, new IngredientTypeTransformer(), false);
    }
}
