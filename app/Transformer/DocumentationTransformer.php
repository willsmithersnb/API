<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class DocumentationTransformer extends ModelTransformer
{
    protected $availableIncludes = ['order', 'fileUpload', 'customer'];

    public function includeOrder(Model $model)
    {
        return $this->item($model->order, new OrderTransformer(), false);
    }

    public function includeFileUpload(Model $model)
    {
        return $this->item($model->fileUpload, new ModelTransformer(), false);
    }

    public function includeCustomer(Model $model)
    {
        return $this->item($model->customer, new CustomerTransformer(), false);
    }
}
