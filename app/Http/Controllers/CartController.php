<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Favorite;
use App\Transformer\CartTransformer;
use App\Transformer\ModelTransformer;
use Illuminate\Http\Request;

class CartController extends ResourceController
{
    protected $model_class = Cart::class;

    protected $url_key = 'cart';

    protected $rule_set = [
        'name' => 'required|string|max:191',
        'customer_id' => 'required|exists:App\Models\Customer,id',
        'custom_components' => 'nullable|json'
    ];

    protected function transformer()
    {
        return new CartTransformer;
    }

    public function store(Request $request)
    {
        $this->except->add('customer_id');
        return parent::storeObject(requestBodyWithCustomerID($request));
    }

    public function show(Cart $cart)
    {
        return parent::showObject($cart);
    }

    public function update(Request $request, Cart $cart)
    {
        $this->except->push('customer_id', 'custom_components');
        return parent::updateObject($request, $cart);
    }

    public function destroy(Cart $cart)
    {
        $favorite = Favorite::where('favoriteable_id', $cart->id)
                            ->where('favoriteable_type', \Str::lower(class_basename($this->model_class)));
        $favorite->delete();
        return parent::destroyObject($cart);
    }
}
