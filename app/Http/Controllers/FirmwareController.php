<?php

namespace App\Http\Controllers;

use App\Models\Firmware;
use App\Transformer\FirmwareTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FirmwareController extends ResourceController
{
    protected $model_class = Firmware::class;

    protected $url_key = 'firmware';

    protected $rule_set = [
        'version' => 'required|string|max:150',
        'expressions' => 'required|string|max:150',
        'notes' => 'required|string|max:150',
        'file_upload_id' => 'required|exists:App\Models\FileUpload,id',
        'uploaded_by' => 'required|exists:App\User,id'
    ];

    protected function transformer()
    {
        return new FirmwareTransformer;
    }

    public function store(Request $request)
    {
        $request->merge(['uploaded_by' => Auth::user()->id]);
        return parent::storeObject($request);
    }

    public function show(Firmware $firmware)
    {
        return parent::showObject($firmware);
    }

    public function update(Request $request, Firmware $firmware)
    {
        $this->except->push('file_upload_id', 'version', 'uploaded_by');
        return parent::updateObject($request, $firmware);
    }

    public function destroy(Firmware $firmware)
    {
        return parent::destroyObject($firmware);
    }
}
