<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Transformer\ModelTransformer;
use Illuminate\Http\Request;

class AddressController extends ResourceController
{
    protected $model_class = Address::class;

    public function rules()
    {
        return [
            'customer_id' => 'required|exists:App\Models\Customer,id',
            'line_1' => 'required|max:191',
            'line_2' => 'max:191',
            'city' => 'required|max:191',
            'state' => 'max:191',
            'zip_code' => 'required|max:10',
            'country' => 'required|max:100',
            'archived_at' => 'sometimes|date',
            'object_hash' => 'sometimes'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transformer()
    {
        return new ModelTransformer;
    }

    public function store(Request $request)
    {
        $hash_array = $request->all();
        $request->merge(['customer_id' => auth()->user()->customer_id, 'object_hash' => md5(implode('|', $hash_array))]);
        return parent::storeObject($request);
    }

    public function show(Address $address)
    {
        return parent::showObject($address);
    }

    public function update(Request $request, Address $address)
    {
        $this->except->add('customer_id');
        return parent::updateObject($request, $address);
    }

    public function destroy(Address $address)
    {
        return parent::destroyObject($address);
    }
}
