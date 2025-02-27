<?php

namespace App\Transformers;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class AuditoriaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'user',
        'campos'
    ];

    public function includeUser(array $audit): Item
    {
        return $this->item($audit['user'], new UserTransformer());
    }
    public function includeCampos(array $audit): Collection
    {
        return $this->collection($audit['campos'], new CamposAuditoriaTransformer());
    }

    public function transform(array $audit)
    {
        return [
            'id' => $audit['id'],
            'acao' => $audit['acao'],
            'user_id' => $audit['user_id'],
            'data_acao' => $audit['data_acao']
        ];
    }
}
