<?php

namespace App\Http\Controllers;

use App\Models\PricingAddon;
use App\Transformer\PricingAddonTransformer;
use Illuminate\Http\Request;

class PricingAddonController extends ResourceController
{
    protected $model_class = PricingAddon::class;

    protected $url_key = 'pricing_addon';

    protected $rule_set = [
        'name' => 'required|string|max:150',
        'conditional_variable'  => 'required|in:num_liters,num_units,num_packaging_options,num_ingredients_changed',
        'pricing_type'  => 'required|in:linear,variable',
        'cost_type' => 'required|in:linear,variable',
        'is_customer_visible'   => 'required|boolean',
        'is_enabled'    => 'required|boolean',
        'is_conditional' => 'required|boolean',
        'powder_enabled' => 'required|boolean',
        'liquid_enabled' => 'required|boolean'
    ];

    protected function transformer()
    {
        return new PricingAddonTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject(requestBodyWithCustomerID($request));
    }

    public function show(PricingAddon $pricing_addon)
    {
        return parent::showObject($pricing_addon);
    }

    public function update(Request $request, PricingAddon $pricing_addon)
    {
        return parent::updateObject($request, $pricing_addon);
    }

    public function destroy(PricingAddon $pricing_addon)
    {
        return parent::destroyObject($pricing_addon);
    }
}
