<?php

namespace App\Transformers;

use App\Models\Pasta;
use League\Fractal\Resource\Item;

class PastaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'perfil'
    ];

    public function includePerfil(Pasta $pasta): Item
    {
        return $this->item($pasta->perfilPasta?->perfil, new PerfilTransformer());
    }

    public function transform(Pasta $pasta)
    {
        return [
            'id' => $pasta->id,
            'nome' => $pasta->nome,
            'tamanho' => $pasta->tamanho,
            'data_alteracao' => $pasta->updated_at->toDateTimeString(),
        ];
    }
}
