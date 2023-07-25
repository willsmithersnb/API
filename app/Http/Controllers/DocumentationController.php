<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use App\Models\FileUpload;
use App\Models\Order;
use App\Transformer\DocumentationTransformer;
use Illuminate\Http\Request;

class DocumentationController extends ResourceController
{
    protected $model_class = Documentation::class;

    protected $url_key = 'documentation';

    protected $rule_set = [
        'name' => 'required|string|max:191',
        'order_id' => 'required|exists:App\Models\Order,id',
        'file_upload_id' => 'required|exists:App\Models\FileUpload,id',
        'customer_id' => 'required|exists:App\Models\Customer,id',
    ];

    protected function transformer()
    {
        return new DocumentationTransformer;
    }

    public function store(Request $request)
    {
        $order = Order::find($request->order_id);
        $request->merge(['customer_id' => optional($order)->customer_id]);
        return parent::storeObject($request);
    }

    public function show(Documentation $documentation)
    {
        return parent::showObject($documentation);
    }

    public function update(Request $request, Documentation $documentation)
    {
        $this->except->push('customer_id', 'order_id', 'file_upload_id');
        return parent::updateObject($request, $documentation);
    }

    public function destroy(Documentation $documentation)
    {
        return parent::destroyObject($documentation);
    }
}
