<?php

namespace App\Transformers;

use App\Models\UserAgenda;
use League\Fractal\Resource\Item;

class UserAgendaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'user',
        'agenda'
    ];

    public function includeUser(UserAgenda $userAgenda): Item
    {
        return $this->item($userAgenda->user, new UserTransformer());
    }

    public function includeAgenda(UserAgenda $userAgenda): Item
    {
        return $this->item($userAgenda->agenda, new AgendaTransformer());
    }

    public function transform(UserAgenda $userAgenda)
    {
        return [
            'id' => $userAgenda->id,
            'user_id' => $userAgenda->user_id,
            'agenda_id' => $userAgenda->agenda_id,
            'user_agenda_logado' => $userAgenda->user_agenda_logado
        ];
    }
}
