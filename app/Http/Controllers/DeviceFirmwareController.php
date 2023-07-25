<?php

namespace App\Http\Controllers;

use App\Models\DeviceFirmware;
use App\Transformer\DeviceFirmwareTransformer;
use Illuminate\Http\Request;

class DeviceFirmwareController extends ResourceController
{
    protected $model_class = DeviceFirmware::class;

    protected $url_key = 'device-firmware';

    protected $rule_set = [
        'device_id' => 'required|exists:App\Models\Device,id',
        'firmware_id' => 'required|exists:App\Models\Firmware,id',
        'installed_at' => 'required|date'
    ];

    protected function transformer()
    {
        return new DeviceFirmwareTransformer;
    }

    public function store(Request $request)
    {
        return parent::storeObject($request);
    }

    public function show(DeviceFirmware $deviceFirmware)
    {
        return parent::showObject($deviceFirmware);
    }

    public function update(Request $request, DeviceFirmware $deviceFirmware)
    {
        return parent::updateObject($request, $deviceFirmware);
    }

    public function destroy(DeviceFirmware $deviceFirmware)
    {
        return parent::destroyObject($deviceFirmware);
    }
}
