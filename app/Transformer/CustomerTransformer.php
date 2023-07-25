<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;


class CustomerTransformer extends ModelTransformer
{
    protected $availableIncludes = ['formulas', 'users', 'messageThreads', 'addresses', 'carts', 'orders', 'quotes', 'coupons'];

    public function includeFormulas(Model $model)
    {
        return $this->collection($model->formulas, new ModelTransformer(), false);
    }

    public function includeUsers(Model $model)
    {
        return $this->collection($model->users, new UserTransformer(), false);
    }

    public function includeMessageThreads(Model $model)
    {
        return $this->collection($model->messageThreads, new MessageThreadTransformer(), false);
    }

    public function includeCarts(Model $model)
    {
        return $this->collection($model->carts, new ModelTransformer(), false);
    }

    public function includeOrders(Model $model)
    {
        return $this->collection($model->orders, new ModelTransformer(), false);
    }

    public function includeQuotes(Model $model)
    {
        return $this->collection($model->quotes, new ModelTransformer(), false);
    }

    public function includeCoupons(Model $model)
    {
        return !is_null($model->coupons) ? $this->collection($model->coupons, new ModelTransformer(), false) : null;
    }
}
