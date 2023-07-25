<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class ItemPricingAddonTierTransformer extends ModelTransformer
{
    protected $availableIncludes = ['pricingAddon', 'pricingAddonTier'];

    public function includePricingAddon(Model $model)
    {
        return !is_null($model->pricingAddon) ? $this->item($model->pricingAddon, new ModelTransformer(), false) : null;
    }

    public function includePricingAddonTier(Model $model)
    {
        return !is_null($model->pricingAddonTier) ? $this->item($model->pricingAddonTier, new ModelTransformer(), false) : null;
    }
}
