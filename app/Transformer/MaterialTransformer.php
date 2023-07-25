<?php

namespace App\Transformer;

use Illuminate\Support\Facades\Auth;

class MaterialTransformer extends ModelTransformer
{
    protected $availableIncludes = ['materialable'];

    function __construct()
    {
        if (!Auth::hasUser() || !auth()->user()->isAdmin()) {
            $this->hiddenFields = ['cost'];
        }
    }

    public function includeMaterialable($model)
    {
        return !is_null($model->materialable) ? $this->item($model->materialable, new ModelTransformer(), false) : null;
    }
}
