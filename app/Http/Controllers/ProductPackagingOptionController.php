<?php

namespace App\Http\Controllers;

use App\Models\ProductPackagingOption;
use App\Transformer\ProductPackagingOptionTransformer;
use Illuminate\Http\Request;

class ProductPackagingOptionController extends ResourceController
{

    protected $model_class = ProductPackagingOption::class;

    protected $url_key = 'product_packaging_option';

    protected $rule_set = [
        'product_id' => 'required|exists:App\Models\Product,id',
        'packaging_option_id' => 'required|exists:App\Models\PackagingOption,id'
    ];

    protected function transformer()
    {
        return new ProductPackagingOptionTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(ProductPackagingOption $productPackagingOption)
    {
        return parent::showObject($productPackagingOption);
    }

    public function update(Request $request, ProductPackagingOption $productPackagingOption)
    {
        return parent::updateObject($request, $productPackagingOption);
    }

    public function destroy(ProductPackagingOption $productPackagingOption)
    {
        return parent::destroyObject($productPackagingOption);
    }
}
