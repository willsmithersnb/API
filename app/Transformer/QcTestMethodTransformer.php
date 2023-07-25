<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class QcTestMethodTransformer extends ModelTransformer
{
    protected $availableIncludes = ['qcTests'];

    function __construct()
    {
        if (!Auth::hasUser() || !auth()->user()->isAdmin()) {
            $this->hiddenFields = ['cost'];
        }
    }

    public function includeQcTests(Model $model)
    {
        return $this->item($model->qcTests, new ModelTransformer(), false);
    }
}
