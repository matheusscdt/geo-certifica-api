<?php

namespace App\Services\Traits;

use App\Serializers\CustomDataArraySerializer;
use App\Transformers\TransacaoTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Scope;

trait DataTransformerTrait
{
    public function createDataTransformerItem($data): Scope
    {
        $fractal = new Manager();
        $fractal->parseIncludes($this->getIncludes());
        $fractal->setSerializer(new CustomDataArraySerializer());
        return $fractal->createData(new Item($data, new TransacaoTransformer()));
    }

    private function createDataTransformerCollection($data): Scope
    {
        $fractal = new Manager();
        $fractal->parseIncludes($this->getIncludes());
        $fractal->setSerializer(new CustomDataArraySerializer());
        return $fractal->createData(new Collection($data, new TransacaoTransformer()));
    }

    private function getIncludes(): array
    {
        $includes = request()->include ?? [];
        return empty($includes) ? [] : explode(',', $includes);
    }
}
