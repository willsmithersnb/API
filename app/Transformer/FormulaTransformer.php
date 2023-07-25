<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class FormulaTransformer extends ModelTransformer
{
    protected $availableIncludes = ['customer', 'parent', 'items', 'products', 'formulaIngredients', 'customIngredients'];

    public function includeCustomer(Model $model)
    {
        return !is_null($model->customer) ? $this->item($model->customer, new CustomerTransformer(), false) : null;
    }

    public function includeParent(Model $model)
    {
        return !is_null($model->parent) ? $this->item($model->parent, new ModelTransformer(), false) : null;
    }

    public function includeItems(Model $model)
    {
        return $this->collection($model->items, new ItemTransformer(), false);
    }

    public function includeProducts(Model $model)
    {
        return $this->collection($model->products, new ItemTransformer(), false);
    }

    public function includeFormulaIngredients(Model $model)
    {
        return $this->collection($model->formulaIngredients, new FormulaIngredientTransformer(), false);
    }

    public function includeCustomIngredients(Model $model)
    {
        return !is_null($model->customIngredients) ? $this->collection($model->customIngredients, new ModelTransformer(), false) : null;
    }
}
