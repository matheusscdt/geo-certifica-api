<?php

namespace App\Transformers;

use App\Models\Perfil;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class PerfilTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'agendas',
        'arquivo'
    ];

    public function includeAgendas(Perfil $perfil): Collection
    {
        return $this->collection($perfil->perfilAgenda->map->agenda, new AgendaTransformer());
    }

    public function includeArquivo(Perfil $perfil): ?Item
    {
        return $perfil->arquivo ? $this->item($perfil->arquivo, new ArquivoTransformer()) : null;
    }

    public function transform(Perfil $perfil)
    {
        return [
            'id' => $perfil->id,
            'nome' => $perfil->nome,
            'perfil_principal' => $perfil->perfil_principal,
            'perfil_ativo' => $perfil->perfil_ativo,
            'proprietario' => $perfil->proprietario
        ];
    }
}
