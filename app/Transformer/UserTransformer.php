<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class UserTransformer extends ModelTransformer
{
    protected $availableIncludes = ['customer', 'roles'];

    public function includeCustomer(Model $model)
    {
        return $this->item($model->customer, new ModelTransformer(), false);
    }

    public function includeRoles(Model $model)
    {
        return $this->collection($model->roles, new ModelTransformer(), false);
    }
}
