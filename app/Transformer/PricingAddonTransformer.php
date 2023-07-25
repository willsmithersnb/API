<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class PricingAddonTransformer extends ModelTransformer
{
    protected $availableIncludes = ['pricingAddonTiers'];

    public function includePricingAddonTiers(Model $model)
    {
        return $this->collection($model->pricingAddonTiers, new PricingAddonTierTransformer(), false);
    }
}
