<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class CouponTransformer extends ModelTransformer
{
    protected $availableIncludes = ['customer'];

    public function includeCustomer(Model $model)
    {
        return !is_null($model->customer) ? $this->item($model->customer, new CustomerTransformer(), false) : null;
    }
}
