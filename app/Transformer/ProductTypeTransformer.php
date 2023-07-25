<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class ProductTypeTransformer extends ModelTransformer
{
    protected $availableIncludes = ['products'];


    public function includeProducts(Model $model)
    {
        return $this->collection($model->products, new ProductTransformer(), false);
    }
}
