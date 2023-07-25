<?php

namespace App\Transformer;

use App\Transformer\GradeIngredientTransformer;
use Illuminate\Database\Eloquent\Model;


class DeviceFirmwareTransformer extends ModelTransformer
{
    protected $availableIncludes = ['device', 'firmware'];

    public function includeDevice(Model $model)
    {
        return $this->item($model->device, new ModelTransformer, false);
    }

    public function includeFirmware(Model $model)
    {
        return $this->item($model->firmware, new ModelTransformer, false);
    }
}
