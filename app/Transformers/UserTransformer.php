<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class UserTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'pessoa',
        'perfis',
        'userPerfis',
        'userPerfisAtivo',
        'userPerfilVinculado',
        'agenda'
    ];

    public function includePessoa(User $user): Item
    {
        return $this->item($user->pessoa, new PessoaTransformer());
    }

    public function includePerfis(User $user): Collection
    {
        return $this->collection($user->perfis(), new PerfilTransformer());
    }

    public function includeAgenda(User $user): ?Item
    {
        return $user->userAgenda?->agenda ? $this->item($user->userAgenda?->agenda, new AgendaTransformer()) : null;
    }

    public function includeUserPerfis(User $user): Collection
    {
        return $this->collection($user->userPerfil, new UserPerfilTransformer());
    }

    public function includeUserPerfisAtivo(User $user): Collection
    {
        return $this->collection($user->userPerfilAtivo(), new UserPerfilTransformer());
    }

    public function includeUserPerfilVinculado(User $user): ?Item
    {
        return $user->userPerfilVinculado() ? $this->item($user->userPerfilVinculado(), new UserPerfilTransformer()) : null;
    }

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'pessoa_id' => $user->pessoa_id,
            'email' => $user->email
        ];
    }
}
