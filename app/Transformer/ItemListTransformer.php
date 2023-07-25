<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class ItemListTransformer extends ModelTransformer
{
    protected $availableIncludes = ['items', 'coupon', 'item_listable', 'itemListChangeLogs'];

    public function includeItems(Model $model)
    {
        return $this->collection($model->items, new ItemTransformer(), false);
    }

    public function includeCoupon(Model $model)
    {
        return !is_null($model->coupon) ? $this->item($model->coupon, new ModelTransformer(), false) : null;
    }

    public function includeItemListable(Model $model)
    {
        return $this->item($model->item_listable, new ModelTransformer(), false);
    }

    public function includeItemListChangeLogs(Model $model)
    {
        return is_null($model->itemListChangeLogs) ? null : $this->collection($model->itemListChangeLogs, new ItemListChangeLogTransformer(), false);
    }
}
