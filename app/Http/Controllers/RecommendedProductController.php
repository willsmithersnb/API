<?php

namespace App\Http\Controllers;

use App\Models\RecommendedProduct;
use App\Transformer\RecommendedProductTransformer;
use Illuminate\Http\Request;

class RecommendedProductController extends ResourceController
{
    protected $model_class = RecommendedProduct::class;

    protected $url_key = 'recommended_product';

    protected $rule_set = [
        'parent_id' => 'required|exists:App\Models\Product,id',
        'product_id' => 'required|exists:App\Models\Product,id',
    ];

    protected function transformer()
    {
        return new RecommendedProductTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(RecommendedProduct $recommendedProduct)
    {
        return parent::showObject($recommendedProduct);
    }

    public function update(Request $request, RecommendedProduct $recommendedProduct)
    {
        return parent::updateObject($request, $recommendedProduct);
    }

    public function destroy(RecommendedProduct $recommendedProduct)
    {
        return parent::destroyObject($recommendedProduct);
    }
}
