<?php

namespace App\Http\Controllers;

use App\Models\ItemPackagingOption;
use App\Transformer\ItemPackagingOptionTransformer;
use Illuminate\Http\Request;

class ItemPackagingOptionController extends ResourceController
{
    protected $model_class = ItemPackagingOption::class;

    protected $url_key = 'item_packaging_option';

    protected $rule_set = [
        'fill_amount' => 'sometimes|integer',
        'quantity' => 'sometimes|integer',
        'price' => 'sometimes|integer',
        'cost' => 'sometimes|integer'
    ];

    protected function transformer()
    {
        return new ItemPackagingOptionTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(ItemPackagingOption $itemPackagingOption)
    {
        return parent::showObject($itemPackagingOption);
    }

    public function update(Request $request, ItemPackagingOption $itemPackagingOption)
    {
        return parent::updateObject($request, $itemPackagingOption);
    }

    public function destroy(ItemPackagingOption $itemPackagingOption)
    {
        return parent::destroyObject($itemPackagingOption);
    }
}
