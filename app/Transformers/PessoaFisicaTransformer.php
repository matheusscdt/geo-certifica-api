<?php

namespace App\Transformers;

use App\Models\PessoaFisica;
use League\Fractal\Resource\Item;

class PessoaFisicaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'pessoa'
    ];

    public function includePessoa(PessoaFisica $pessoaFisica): Item
    {
        return $this->item($pessoaFisica->pessoa, new PessoaTransformer());
    }

    public function transform(PessoaFisica $pessoaFisica)
    {
        return [
            'id' => $pessoaFisica->id,
            'pessoa_id' => $pessoaFisica->pessoa_id,
            'cpf' => $pessoaFisica->cpf,
            'data_nascimento' => $pessoaFisica->data_nascimento->toDateString(),
        ];
    }
}
