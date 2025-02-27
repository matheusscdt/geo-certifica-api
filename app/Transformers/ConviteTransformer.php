<?php

namespace App\Transformers;

use App\Models\Convite;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class ConviteTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'perfil',
        'pastas'
    ];

    public function includePerfil(Convite $convite): Item
    {
        return $this->item($convite->perfil, new PerfilTransformer());
    }

    public function includePastas(Convite $convite): Collection
    {
        return $this->collection($convite->pastas(), new PastaTransformer());
    }

    public function transform(Convite $convite)
    {
        return [
            'id' => $convite->id,
            'perfil_id' => $convite->perfil_id,
            'nome' => $convite->nome,
            'email' => $convite->email,
            'aceite' => $convite->aceite,
            'data_aceite' => $convite->data_aceite?->toDateTimeString(),
            'gestor' => $convite->gestor
        ];
    }
}
