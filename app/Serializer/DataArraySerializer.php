<?php

namespace App\Serializer;

/**
 * Create a new Serializer in your project
 */

use League\Fractal\Serializer\ArraySerializer;

class DataArraySerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        return $resourceKey === false ? $data : ['data' => $data];
    }

    public function item($resourceKey, array $data)
    {
        return $resourceKey === false ? $data : ['data' => $data];
    }
}
