<?php

namespace App\Transformer;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Model;

class FavoriteTransformer extends ModelTransformer
{
    protected $availableIncludes = ['favoriteable'];

    protected function getFavoriteableClass($favoriteable)
    {
        $favoriteable_transformer = null;
        switch (get_class($favoriteable)) {
            case Quote::class:
                $favoriteable_transformer = QuoteTransformer::class;
                break;

            case Cart::class:
                $favoriteable_transformer = CartTransformer::class;
                break;

            case Order::class:
                $favoriteable_transformer = OrderTransformer::class;
                break;

            default:
                $favoriteable_transformer = ModelTransformer::class;
                break;
        }
        return new $favoriteable_transformer();
    }

    public function includeFavoriteable(Model $model)
    {
        $favoriteable = $model->favoriteable;
        return is_null($favoriteable) ? null : $this->item($favoriteable, $this->getFavoriteableClass($favoriteable), false);
    }
}
