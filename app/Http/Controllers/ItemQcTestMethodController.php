<?php

namespace App\Http\Controllers;

use App\Models\ItemQcTestMethod;
use App\Transformer\ItemQcTestMethodTransformer;
use Illuminate\Http\Request;

class ItemQcTestMethodController extends ResourceController
{
    protected $model_class = ItemQcTestMethod::class;

    protected $url_key = 'item_qc_test_method';

    protected $rule_set = [
        'price' => 'sometimes|integer',
        'cost' => 'sometimes|integer'
    ];

    protected function transformer()
    {
        return new ItemQcTestMethodTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(ItemQcTestMethod $itemQcTestMethod)
    {
        return parent::showObject($itemQcTestMethod);
    }

    public function update(Request $request, ItemQcTestMethod $itemQcTestMethod)
    {
        return parent::updateObject($request, $itemQcTestMethod);
    }

    public function destroy(ItemQcTestMethod $itemQcTestMethod)
    {
        return parent::destroyObject($itemQcTestMethod);
    }
}
