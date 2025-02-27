<?php

namespace App\Transformers;

use App\Models\Mensagem;
use League\Fractal\Resource\Item;

class MensagemTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'documento'
    ];

    public function includeDocumento(Mensagem $mensagem): Item
    {
        return $this->item($mensagem->documento, new DocumentoTransformer());
    }

    public function transform(Mensagem $mensagem)
    {
        return [
            'id' => $mensagem->id,
            'documento_id' => $mensagem->documento_id,
            'assunto' => $mensagem->assunto,
            'mensagem' => $mensagem->mensagem,
        ];
    }
}
