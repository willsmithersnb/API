<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class ItemPackagingOptionTransformer extends ModelTransformer
{
    protected $availableIncludes = ['pods', 'packagingOption'];

    public function includePods(Model $model)
    {
        return $this->collection($model->Pods, new ModelTransformer(), false);
    }

    public function includePackagingOption(Model $model)
    {
        return !is_null($model->PackagingOption) ? $this->item($model->PackagingOption, new ModelTransformer(), false) : null;
    }
}
