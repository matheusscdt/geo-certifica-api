<?php

namespace App\Transformers;

use App\Models\UserPerfil;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class UserPerfilTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'user',
        'perfil',
        'pastas'
    ];

    public function includeUser(UserPerfil $userPerfil): Item
    {
        return $this->item($userPerfil->user, new UserTransformer());
    }

    public function includePerfil(UserPerfil $userPerfil): Item
    {
        return $this->item($userPerfil->perfil, new PerfilTransformer());
    }

    public function includePastas(UserPerfil $userPerfil): Collection
    {
        return $this->collection($userPerfil->pastas, new PastaTransformer());
    }

    public function transform(UserPerfil $userPerfil)
    {
        return [
            'id' => $userPerfil->id,
            'user_id' => $userPerfil->user_id,
            'perfil_id' => $userPerfil->perfil_id,
            'perfil_principal' => $userPerfil->perfil_principal,
            'perfil_ativo' => $userPerfil->perfil_ativo,
            'gestor' => $userPerfil->gestor,
            'ativo' => $userPerfil->ativo
        ];
    }
}
