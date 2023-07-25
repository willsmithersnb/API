<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Transformer\FavoriteTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends ResourceController
{
    protected $model_class = Favorite::class;

    protected $url_key = 'favorite';

    protected $rule_set = [
        'name' => 'required|string|max:191',
        'favoriteable_type' => 'sometimes',
        'favoriteable_id' => 'required|poly_exists:favoriteable_type'
    ];

    protected function transformer()
    {
        return new FavoriteTransformer;
    }

    public function store(Request $request)
    {
        $this->rule_set['customer_id'] = 'required|exists:App\Models\Customer,id';
        $request->merge(['customer_id' => Auth::user()->customer_id]);
        return parent::storeObject($request);
    }

    public function show(Favorite $favorite)
    {
        return parent::showObject($favorite);
    }

    public function update(Request $request, Favorite $favorite)
    {
        $this->except->push('favoriteable_type', 'favoriteable_id', 'customer_id');
        return parent::updateObject($request, $favorite);
    }

    public function destroy(Favorite $favorite)
    {
        return parent::destroyObject($favorite);
    }
}
