<?php

namespace App\Transformers;

use App\Models\Configuracao;
use League\Fractal\Resource\Item;

class ConfiguracaoTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'documento'
    ];

    public function includeDocumento(Configuracao $configuracao): Item
    {
        return $this->item($configuracao->documento, new DocumentoTransformer());
    }

    public function transform(Configuracao $configuracao)
    {
        return [
            'id' => $configuracao->id,
            'documento_id' => $configuracao->documento_id,
            'data_limite_assinatura' => $configuracao->data_limite_assinatura->toDateString(),
            'lembrete_documento' => $configuracao->lembrete_documento
        ];
    }
}
