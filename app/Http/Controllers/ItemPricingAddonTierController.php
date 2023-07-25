<?php

namespace App\Http\Controllers;

use App\Models\ItemPricingAddonTier;
use App\Transformer\ItemPricingAddonTierTransformer;
use Illuminate\Http\Request;

class ItemPricingAddonTierController extends ResourceController
{
    protected $model_class = ItemPricingAddonTier::class;

    protected $url_key = 'item_pricing_addon_tier';

    protected $rule_set = [
        'price' => 'sometimes|integer',
        'cost' => 'sometimes|integer'
    ];

    protected function transformer()
    {
        return new ItemPricingAddonTierTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(ItemPricingAddonTier $itemPricingAddonTier)
    {
        return parent::showObject($itemPricingAddonTier);
    }

    public function update(Request $request, ItemPricingAddonTier $itemPricingAddonTier)
    {
        return parent::updateObject($request, $itemPricingAddonTier);
    }

    public function destroy(ItemPricingAddonTier $itemPricingAddonTier)
    {
        return parent::destroyObject($itemPricingAddonTier);
    }
}
