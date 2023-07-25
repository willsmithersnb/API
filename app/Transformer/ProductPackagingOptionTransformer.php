<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class ProductPackagingOptionTransformer extends ModelTransformer
{
    protected $availableIncludes = ['packagingOption'];

    public function includePackagingOption(Model $model)
    {
        return !is_null($model->packagingOption) ? $this->item($model->packagingOption, new PackagingOptionTransformer(), false) : null;
    }
}
