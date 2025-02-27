<?php

namespace App\Transformers;

use App\Models\Pessoa;
use League\Fractal\Resource\Item;

class PessoaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'pessoaFisica',
        'pessoaJuridica',
        'user'
    ];

    public function includePessoaFisica(Pessoa $pessoa): ?Item
    {
        return $pessoa->pessoaFisica ? $this->item($pessoa->pessoaFisica, new PessoaFisicaTransformer()) : null;
    }

    public function includePessoaJuridica(Pessoa $pessoa): ?Item
    {
        return $pessoa->pessoaJuridica ? $this->item($pessoa->pessoaJuridica, new PessoaJuridicaTransformer()) : null;
    }

    public function includeUser(Pessoa $pessoa): Item
    {
        return $this->item($pessoa->user, new UserTransformer());
    }

    public function transform(Pessoa $pessoa)
    {
        return [
            'id' => $pessoa->id,
            'nome' => $pessoa->nome
        ];
    }
}
