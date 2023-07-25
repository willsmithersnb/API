<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast\Array_;

class FirmwareTransformer extends ModelTransformer
{
    protected $availableIncludes = ['fileUpload', 'user'];

    public function includeFileUpload(Model $model)
    {
        return $this->item($model->fileUpload, new ModelTransformer(), false);
    }

    public function includeUser(Model $model)
    {
        return $this->item($model->user, new UserTransformer(), false);
    }
}
