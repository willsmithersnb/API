<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class ProductOptionTransformer extends ModelTransformer
{
    protected $availableIncludes = ['productPackagingOptions', 'productQcTests'];

    public function includeProductPackagingOptions(Model $model)
    {
        return $this->collection($model->productPackagingOptions, new ProductPackagingOptionTransformer(), false);
    }

    public function includeProductQcTests(Model $model)
    {
        return $this->collection($model->productQcTests, new ProductQcTestTransformer(), false);
    }
}
