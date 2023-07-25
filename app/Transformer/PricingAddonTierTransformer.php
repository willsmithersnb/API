<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PricingAddonTierTransformer extends ModelTransformer
{
    protected $availableIncludes = ['pricingAddon'];

    function __construct()
    {
        if (!Auth::hasUser() || !auth()->user()->isAdmin()) {
            $this->hiddenFields = ['cost'];
        }
    }

    public function includePricingAddon(Model $model)
    {
        return $this->item($model->pricingAddon, new PricingAddonTransformer(), false);
    }
}
