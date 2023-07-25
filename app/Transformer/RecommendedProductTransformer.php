<?php

namespace App\Transformer;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class RecommendedProductTransformer extends ModelTransformer
{
    protected $availableIncludes = ['product'];

    public function includeProduct(Model $model)
    {
        return !is_null($model->product) ? $this->item($model->product, new ProductTransformer(), false) : null;
    }
}
