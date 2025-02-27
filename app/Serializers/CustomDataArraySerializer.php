<?php

namespace App\Serializers;

use League\Fractal\Serializer\DataArraySerializer;

class CustomDataArraySerializer extends DataArraySerializer
{
    public function collection($resourceKey, array $data): array
    {
        if ($resourceKey === 'false') {
            return $data;
        }

        return ['data' => $data];
    }

    public function item($resourceKey, array $data): array
    {
        if ($resourceKey === 'false') {
            return $data;
        }

        return ['data' => $data];
    }
}
