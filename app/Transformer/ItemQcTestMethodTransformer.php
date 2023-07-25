<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class ItemQcTestMethodTransformer extends ModelTransformer
{
    protected $availableIncludes = ['qcTest', 'qcTestMethod'];

    public function includeQcTest(Model $model)
    {
        return !is_null($model->qcTest) ? $this->item($model->qcTest, new ModelTransformer(), false) : null;
    }

    public function includeQcTestMethod(Model $model)
    {
        return !is_null($model->qcTestMethod) ? $this->item($model->qcTestMethod, new ModelTransformer(), false) : null;
    }
}
