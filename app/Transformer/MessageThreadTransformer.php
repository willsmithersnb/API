<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class MessageThreadTransformer extends ModelTransformer
{
    protected $availableIncludes = ['customer', 'messages', 'lastMessage'];

    public function includeCustomer(Model $model)
    {
        return $this->item($model->customer, new ModelTransformer(), false);
    }

    public function includeMessages(Model $model)
    {
        return $this->collection($model->messages, new MessageTransformer(), false);
    }

    public function includeLastMessage(Model $model)
    {
        return $this->item($model->lastMessage->last(), new MessageTransformer(), false);
    }
}
