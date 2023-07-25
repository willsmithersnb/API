<?php

namespace App\Transformer;

use App\Models\ProductQcTest;
use App\Models\RecommendedProduct;
use Illuminate\Database\Eloquent\Model;

class ProductTransformer extends ModelTransformer
{
    protected $availableIncludes = ['catalogs', 'productType', 'formula', 'productOptions', 'recommendedProducts', 'productImageUploads', 'productDocuments'];


    public function includeCatalogs(Model $model)
    {
        return $this->collection($model->catalogs, new ModelTransformer(), false);
    }

    public function includeProductType(Model $model)
    {
        return $this->item($model->productType, new ModelTransformer(), false);
    }

    public function includeFormula(Model $model)
    {
        return $this->item($model->formula, new FormulaTransformer(), false);
    }

    public function includeItems(Model $model)
    {
        return $this->collection($model->items, new ModelTransformer(), false);
    }

    public function includeProductOptions(Model $model)
    {
        return $this->collection($model->productOptions, new ProductOptionTransformer(), false);
    }

    public function includeRecommendedProducts(Model $model)
    {
        return $this->collection($model->recommendedProducts, new RecommendedProductTransformer(), false);
    }

    public function includeProductImageUploads(Model $model)
    {
        return $this->collection($model->productImageUploads, new ModelTransformer(), false);
    }

    public function includeProductDocuments(Model $model)
    {
        return $this->collection($model->productDocuments, new ProductDocumentTransformer(), false);
    }
}
