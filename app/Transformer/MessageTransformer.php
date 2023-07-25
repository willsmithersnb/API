<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class MessageTransformer extends ModelTransformer
{
    protected $availableIncludes = ['user'];

    public function includeUser(Model $model)
    {
        return $this->item($model->user, new UserTransformer(), false);
    }

    public function includeMessages(Model $model)
    {
        return $this->collection($model->messageThread, new MessageThreadTransformer(), false);
    }
}
