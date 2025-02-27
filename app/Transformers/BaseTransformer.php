<?php

namespace App\Transformers;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    public function item($data, $transformer, $resourceKey = false): Item
    {
        $resourceKeyString = $this->getResourceKeyString($resourceKey);
        return parent::item($data, $transformer, $resourceKeyString);
    }

    public function collection($data, $transformer, ?string $resourceKey = null): Collection
    {
        $resourceKeyString = $this->getResourceKeyString($resourceKey);
        return parent::collection($data, $transformer, $resourceKeyString);
    }

    private function getResourceKeyString($resourceKey): string
    {
        return $resourceKey ? 'true' : 'false';
    }
}
