<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class OrderTransformer extends ModelTransformer
{
    protected $availableIncludes = ['customer', 'itemList', 'favorite', 'documents', 'user', 'billingAddress', 'shippingAddress'];

    public function includeCustomer(Model $model)
    {
        return $this->item($model->customer, new CustomerTransformer(), false);
    }

    public function includeUser(Model $model)
    {
        return !is_null($model->user) ? $this->item($model->user, new UserTransformer(), false) : null;
    }

    public function includeItemList(Model $model)
    {
        return !is_null($model->itemList) ? $this->item($model->itemList, new ItemListTransformer(), false) : null;
    }

    public function includeFavorite(Model $model)
    {
        return !is_null($model->favorite) ? $this->item($model->favorite, new FormulaTransformer(), false) : null;
    }

    public function includeDocuments(Model $model)
    {
        return $this->collection($model->documents, new DocumentationTransformer(), false);
    }

    public function includeShippingAddress(Model $model)
    {
        return !is_null($model->shippingAddress) ? $this->item($model->shippingAddress, new ModelTransformer(), false) : null;
    }

    public function includeBillingAddress(Model $model)
    {
        return !is_null($model->billingAddress) ? $this->item($model->billingAddress, new ModelTransformer(), false) : null;
    }
}
