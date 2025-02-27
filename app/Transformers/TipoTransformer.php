<?php

namespace App\Transformers;

use App\Models\Tipo;
use League\Fractal\Resource\Item;

class TipoTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'perfil'
    ];

    public function includePerfil(Tipo $tipo): Item
    {
        return $this->item($tipo->perfil, new PerfilTransformer());
    }

    public function transform(Tipo $tipo)
    {
        return [
            'id' => $tipo->id,
            'perfil_id' => $tipo->perfil_id,
            'descricao' => $tipo->descricao
        ];
    }
}
