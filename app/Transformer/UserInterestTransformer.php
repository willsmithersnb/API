<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class UserInterestTransformer extends ModelTransformer
{
    protected $availableIncludes = ['cellMedia', 'user'];

    public function includeCellMedia(Model $model)
    {
        return $this->item($model->cellMedia, new ModelTransformer(), false);
    }

    public function includeUser(Model $model)
    {
        return $this->item($model->user, new ModelTransformer(), false);
    }
}
