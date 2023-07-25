<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Transformer\ProductTypeTransformer;
use Illuminate\Http\Request;

class ProductTypeController extends ResourceController
{
    protected $model_class = ProductType::class;

    protected $url_key = 'product_type';

    protected $rule_set = [
        'name' => 'required|string|max:191'
    ];

    protected function transformer()
    {
        return new ProductTypeTransformer();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  ProductType $product_type
     * @return \Illuminate\Http\Response
     */
    public function show(ProductType $product_type)
    {
        return parent::showObject($product_type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  ProductType $product_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductType $product_type)
    {
        return parent::updateObject($request, $product_type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ProductType $product_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductType $product_type)
    {
        return parent::destroyObject($product_type);
    }
}
