<?php

namespace App\Transformers;

use App\Models\Autorizacao;
use League\Fractal\Resource\Item;

class AutorizacaoTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'destinatario',
        'assinatura'
    ];

    public function includeDestinatario(Autorizacao $autorizacao): Item
    {
        return $this->item($autorizacao->destinatario, new DestinatarioTransformer());
    }

    public function includeAssinatura(Autorizacao $autorizacao): ?Item
    {
        return $autorizacao->assinatura ? $this->item($autorizacao->assinatura, new AssinaturaTransformer()) : null;
    }

    public function transform(Autorizacao $autorizacao)
    {
        return [
            'id' => $autorizacao->id,
            'destinatario_id' => $autorizacao->destinatario_id,
            'data_validade' => $autorizacao->data_validade->toDateTimeString(),
            'autorizado' => $autorizacao->autorizado
        ];
    }
}
