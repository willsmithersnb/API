<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductQcTest;
use App\Transformer\ProductQcTestTransformer;
use Illuminate\Http\Request;

class ProductQcTestController extends ResourceController
{
    protected $model_class = ProductQcTest::class;

    protected $url_key = 'product_qc_test';

    protected $rule_set = [
        'product_id' => 'required|exists:App\Models\Product,id',
        'qc_test_id' => 'required|exists:App\Models\QcTest,id'
    ];

    protected function transformer()
    {
        return new ProductQcTestTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(ProductQcTest $productQcTest)
    {
        return parent::showObject($productQcTest);
    }

    public function update(Request $request, ProductQcTest $productQcTest)
    {
        return parent::updateObject($request, $productQcTest);
    }

    public function destroy(ProductQcTest $productQcTest)
    {
        return parent::destroyObject($productQcTest);
    }
}
