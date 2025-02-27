<?php

namespace App\Transformers;

use App\Models\Assinatura;
use League\Fractal\Resource\Item;

class AssinaturaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'autorizacao'
    ];

    public function includeAutorizacao(Assinatura $assinatura): Item
    {
        return $this->item($assinatura->autorizacao, new AutorizacaoTransformer());
    }

    public function transform(Assinatura $assinatura)
    {
        return [
            'id' => $assinatura->id,
            'autorizacao_id' => $assinatura->autorizacao_id,
            'cpf' => $assinatura->cpf,
            'data_nascimento' => $assinatura->data_nascimento->toDateString(),
            'data_assinatura' => $assinatura->data_assinatura->toDateTimeString(),
            'ip_address' => $assinatura->ip_address,
            'dispositivo' => $assinatura->dispositivo
        ];
    }
}
