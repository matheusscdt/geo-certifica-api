<?php

namespace App\Transformers;

use App\Models\Agenda;
use League\Fractal\Resource\Item;

class AgendaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'userAgenda'
    ];

    public function includeUserAgenda(Agenda $agenda): ?Item
    {
        return $agenda->userAgenda ? $this->item($agenda->userAgenda, new UserAgendaTransformer()) : null;
    }

    public function transform(Agenda $agenda)
    {
        return [
            'id' => $agenda->id,
            'nome' => $agenda->nome,
            'email' => $agenda->email,
            'cpf' => $agenda->cpf
        ];
    }
}
