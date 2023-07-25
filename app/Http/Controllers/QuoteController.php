<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Quote;
use App\Transformer\QuoteTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class QuoteController extends ResourceController
{
    protected $model_class = Quote::class;

    protected $url_key = 'quote';

    protected $rule_set = [
        'name' => 'required|string|max:191',
        'customer_id' => 'required|exists:App\Models\Customer,id',
        'custom_components' => 'nullable|json',
        'billing_address_id' => 'required|exists:App\Models\Address,id',
        'shipping_address_id' => 'required|exists:App\Models\Address,id',
        'is_orderable' => 'sometimes|boolean',
        'expires_at' => 'sometimes',
        'price_visible_to_customer' => 'sometimes|boolean'
    ];

    protected function transformer()
    {
        return new QuoteTransformer;
    }

    public function store(Request $request)
    {
        $this->except->add('customer_id');
        return parent::storeObject(requestBodyWithCustomerID($request));
    }

    public function show(Quote $quote)
    {
        return parent::showObject($quote);
    }

    public function update(Request $request, Quote $quote)
    {
        $this->except->push('customer_id', 'custom_components', 'billing_address_id', 'shipping_address_id');
        return parent::updateObject($request, $quote);
    }

    public function destroy(Quote $quote)
    {
        $favorite = Favorite::where('favoriteable_id', $quote->id)
                            ->where('favoriteable_type', \Str::lower(class_basename($this->model_class)));
        $favorite->delete();
        return parent::destroyObject($quote);
    }
}
