<?php

namespace App\Http\Controllers;

use App\Models\PricingAddonTier;
use App\Transformer\PricingAddonTierTransformer;
use Illuminate\Http\Request;

class PricingAddonTierController extends ResourceController
{
    protected $model_class = PricingAddonTier::class;

    protected $url_key = 'pricing_addon_tier';

    protected $rule_set = [
        'pricing_addon_id' => 'required|exists:App\Models\PricingAddon,id',
        'condition_greater_than' => 'required|integer|digits_between:0,18',
        'price' => 'required|integer|digits_between:0,18',
        'cost' => 'required|integer|digits_between:0,18'
    ];

    protected function transformer()
    {
        return new PricingAddonTierTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject(requestBodyWithCustomerID($request));
    }

    public function show(PricingAddonTier $pricing_addon_tier)
    {
        return parent::showObject($pricing_addon_tier);
    }

    public function update(Request $request, PricingAddonTier $pricing_addon_tier)
    {
        return parent::updateObject($request, $pricing_addon_tier);
    }

    public function destroy(PricingAddonTier $pricing_addon_tier)
    {
        return parent::destroyObject($pricing_addon_tier);
    }
}
