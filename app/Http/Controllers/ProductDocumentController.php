<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Models\ProductDocument;
use App\Transformer\ProductDocumentTransformer;
use Illuminate\Http\Request;

class ProductDocumentController extends ResourceController
{
    protected $model_class = ProductDocument::class;

    protected $url_key = 'product_document';

    protected $rule_set = [
        'name' => 'required|max:191',
        'product_id' => 'required|exists:App\Models\Product,id',
        'file_upload_id' => 'required|exists:App\Models\FileUpload,id',
        'document_url' => 'sometimes'
    ];

    public function __construct()
    {
        $this->authorizeResource($this->model_class, $this->url_key, [
            'except' => ['show', 'destroy'],
        ]);
    }

    protected function transformer()
    {
        return new ProductDocumentTransformer;
    }

    public function store(Request $request)
    {
        $file = FileUpload::findOrFail($request->file_upload_id);
        $file->makePublic();
        $request->merge(['file_upload_id' => $file->id, 'document_url' => $file->public_url]);
        return parent::storeObject($request);
    }

    public function show(int $id)
    {
        $productDocument = ProductDocument::findOrFail($id);
        return parent::showObject($productDocument);
    }

    public function update(Request $request, ProductDocument $productDocument)
    {
        ProductDocument::where('product_id', $productDocument->product_id)->delete();
        $file = FileUpload::findOrFail($request->file_upload_id);
        $file->makePublic();
        $request->merge(['file_upload_id' => $file->id, 'document_url' => $file->public_url]);
        return parent::updateObject($request, $productDocument);
    }

    public function destroy(int $id)
    {
        $productDocument = ProductDocument::findOrFail($id);
        return parent::destroyObject($productDocument);
    }
}
