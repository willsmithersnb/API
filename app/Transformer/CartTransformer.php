<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class CartTransformer extends ModelTransformer
{
    protected $availableIncludes = ['itemList', 'favorite', 'customer', 'user'];

    public function includeItemList(Model $model)
    {
        return !is_null($model->itemList) ? $this->item($model->itemList, new ItemListTransformer(), false) : null;
    }

    public function includeFavorite(Model $model)
    {
        return !is_null($model->favorite) ? $this->item($model->favorite, new FormulaTransformer(), false) : null;
    }

    public function includeCustomer(Model $model)
    {
        return $this->item($model->customer, new CustomerTransformer(), false);
    }

    public function includeUser(Model $model)
    {
        return $this->item($model->user, new UserTransformer(), false);
    }
}
