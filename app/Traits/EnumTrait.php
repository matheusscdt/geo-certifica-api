<?php

namespace App\Traits;

use App\Transformers\EnumTransformer;
use Illuminate\Support\Collection;
use League\Fractal\Manager;
use League\Fractal\Scope;

trait EnumTrait
{
    public static function getCollectionTransform(): Scope
    {
        $fractal = new Manager();
        $transformer = new EnumTransformer();
        $collection = $transformer->collection(self::cases(), $transformer, true);
        return $fractal->createData($collection);
    }

    public static function casesCollection(): Collection
    {
        return collect(self::cases());
    }
}
