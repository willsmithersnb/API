<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class ProductDocumentTransformer extends ModelTransformer
{
    protected $availableIncludes = ['product'];

    public function includeProduct(Model $model)
    {
        return $this->item($model->product, new ModelTransformer(), false);
    }

}
