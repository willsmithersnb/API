<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;


class QcTestTransformer extends ModelTransformer
{
    protected $availableIncludes = ['qcTestMethods'];

    public function includeQcTestMethods(Model $model)
    {
        return $this->collection($model->QcTestMethods, new QcTestMethodTransformer(), false);
    }
}
