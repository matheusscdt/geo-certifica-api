<?php

namespace App\Transformers;

use App\Models\PessoaFisica;
use App\Models\PessoaJuridica;
use League\Fractal\Resource\Item;

class PessoaJuridicaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'pessoa'
    ];

    public function includePessoa(PessoaFisica $pessoaFisica): Item
    {
        return $this->item($pessoaFisica->pessoa, new PessoaTransformer());
    }

    public function transform(PessoaJuridica $pessoaJuridica)
    {
        return [
            'id' => $pessoaJuridica->id,
            'pessoa_id' => $pessoaJuridica->pessoa_id,
            'cnpj' => $pessoaJuridica->cnpj
        ];
    }
}
