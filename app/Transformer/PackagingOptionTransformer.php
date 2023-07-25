<?php

namespace App\Transformer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;


class PackagingOptionTransformer extends ModelTransformer
{
    protected $availableIncludes = ['itemPackagingOptions', 'materials', 'customer'];

    function __construct()
    {
        if (!Auth::hasUser() || !auth()->user()->isAdmin()) {
            $this->hiddenFields = ['cost', 'fill_cost_per_litre'];
        }
    }

    public function includeItemPackagingOptions($model)
    {
        return $this->collection($model->itemPackagingOptions, new ModelTransformer(), false);
    }

    public function includeMaterials(Model $model)
    {
        return $this->collection($model->materials, new MaterialTransformer(), false);
    }

    public function includeCustomer($model)
    {
        return !is_null($model->customer) ? $this->item($model->customer, new CustomerTransformer(), false) : null;
    }
}
