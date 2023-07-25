<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class ProductQcTestTransformer extends ModelTransformer
{
    protected $availableIncludes = ['qcTest', 'qcTestMethod'];

    public function includeQcTest(Model $model)
    {
        return $this->item($model->qcTest, new ModelTransformer(), false);
    }

    public function includeQcTestMethod(Model $model)
    {
        return  !is_null($model->qcTestMethod) ? $this->item($model->qcTestMethod, new ModelTransformer(), false) : null;
    }
}
