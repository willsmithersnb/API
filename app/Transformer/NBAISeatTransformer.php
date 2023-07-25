<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class NBAISeatTransformer extends ModelTransformer
{
    protected $availableIncludes = ['user'];

    public function includeUser(Model $model)
    {
        return !is_null($model->user) ? $this->item($model->user, new ModelTransformer(), false) : null;
    }
}
