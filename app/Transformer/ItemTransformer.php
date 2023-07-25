<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class ItemTransformer extends ModelTransformer
{
    protected $availableIncludes = ['product', 'itemSummary', 'formula', 'itemList', 'itemPackagingOptions', 'itemPricingRule', 'itemQcTestMethod', 'itemPricingAddonTiers'];

    public function includeProduct(Model $model)
    {
        return !is_null($model->product) ? $this->item($model->product, new ProductTransformer(), false) : null;
    }

    public function includeItemSummary(Model $model)
    {
        return !is_null($model->itemSummary) ? $this->item($model->itemSummary, new ModelTransformer(), false) : null;
    }

    public function includeFormula(Model $model)
    {
        return !is_null($model->formula) ? $this->item($model->formula, new FormulaTransformer(), false) : null;
    }

    public function includeItemList(Model $model)
    {
        return $this->item($model->itemList, new ModelTransformer(), false);
    }

    public function includeItemPackagingOptions(Model $model)
    {
        return $this->collection($model->itemPackagingOptions, new ItemPackagingOptionTransformer(), false);
    }

    public function includeItemPricingRule(Model $model)
    {
        return $this->item($model->itemPricingRule, new ModelTransformer(), false);
    }

    public function includeItemQcTestMethod(Model $model)
    {
        return $this->collection($model->itemQcTestMethod, new ItemQcTestMethodTransformer(), false);
    }

    public function includeItemPricingAddonTiers(Model $model)
    {
        return $this->collection($model->itemPricingAddonTiers, new ItemPricingAddonTierTransformer(), false);
    }
}
