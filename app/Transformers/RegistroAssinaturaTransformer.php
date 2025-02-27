<?php

namespace App\Transformers;

use App\Models\RegistroAssinatura;
use League\Fractal\Resource\Item;

class RegistroAssinaturaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'documento',
        'assinatura',
        'arquivoAssinado'
    ];

    public function includeDocumento(RegistroAssinatura $registroAssinatura): Item
    {
        return $this->item($registroAssinatura->documento, new DocumentoTransformer());
    }

    public function includeAssinatura(RegistroAssinatura $registroAssinatura): Item
    {
        return $this->item($registroAssinatura->assinatura, new AssinaturaTransformer());
    }

    public function includeArquivoAssinado(RegistroAssinatura $registroAssinatura): Item
    {
        return $this->item($registroAssinatura->arquivoAssinado, new ArquivoTransformer());
    }


    public function transform(RegistroAssinatura $registroAssinatura)
    {
        return [
            'id' => $registroAssinatura->id,
            'documento_id' => $registroAssinatura->documento_id,
            'assinatura_id' => $registroAssinatura->assinatura_id,
            'arquivo_assinado_id' => $registroAssinatura->arquivo_assinado_id,
            'ordem' => $registroAssinatura->ordem,
            'hash' => $registroAssinatura->hash,
            'data_criacao' => $registroAssinatura->created_at->toDateTimeString(),
        ];
    }
}
