<?php

namespace App\Http\Controllers;

use App\Models\PricingRule;
use App\Transformer\ModelTransformer;
use Illuminate\Http\Request;

class PricingRuleController extends ResourceController
{
    protected $model_class = PricingRule::class;

    protected $url_key = 'pricing_rule';

    protected $rule_set = [
        'name' => 'required|string|max:191',
        'condition' => 'required|string|max:191',
        'price' => 'required|numeric',
        'cost' => 'required|integer',
        'has_custom_price' => 'required|boolean'
    ];

    protected function transformer()
    {
        return new ModelTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(PricingRule $pricing_rule)
    {
        return parent::showObject($pricing_rule);
    }

    public function update(Request $request, PricingRule $pricing_rule)
    {
        return parent::updateObject($request, $pricing_rule);
    }

    public function destroy(PricingRule $pricing_rule)
    {
        return parent::destroyObject($pricing_rule);
    }
}
