<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class PodTransformer extends ModelTransformer
{
    protected $availableIncludes = ['itemPackagingOption'];

    public function includeItemPackagingOption(Model $model)
    {
        return $this->item($model->itemPackagingOption, new ModelTransformer(), false);
    }
}
