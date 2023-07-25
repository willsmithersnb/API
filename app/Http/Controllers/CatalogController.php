<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Transformer\CatalogTransformer;
use Illuminate\Http\Request;

class CatalogController extends ResourceController
{
    protected $model_class = Catalog::class;

    protected $url_key = 'catalog';

    protected $rule_set = [
        'number' => 'required|string|max:150',
        'product_id' => 'required|exists:App\Models\Product,id'
    ];

    protected function transformer()
    {
        return new CatalogTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(Catalog $catalog)
    {
        return parent::showObject($catalog);
    }

    public function update(Request $request, Catalog $catalog)
    {
        return parent::updateObject($request, $catalog);
    }

    public function destroy(Catalog $catalog)
    {
        return parent::destroyObject($catalog);
    }
}
