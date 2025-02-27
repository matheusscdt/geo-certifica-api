<?php

namespace App\Transformers;

use App\Models\RegistroAssinaturaInterna;
use League\Fractal\Resource\Item;

class RegistroAssinaturaInternaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'documento',
        'certificado',
        'arquivoOriginal',
        'arquivoAssinado'
    ];

    public function includeDocumento(RegistroAssinaturaInterna $registroAssinaturaInterna): Item
    {
        return $this->item($registroAssinaturaInterna->documento, new DocumentoTransformer());
    }

    public function includeCertificado(RegistroAssinaturaInterna $registroAssinaturaInterna): Item
    {
        return $this->item($registroAssinaturaInterna->certificado, new CertificadoTransformer());
    }

    public function includeArquivoOriginal(RegistroAssinaturaInterna $registroAssinaturaInterna): Item
    {
        return $this->item($registroAssinaturaInterna->arquivoOriginal, new ArquivoTransformer());
    }

    public function includeArquivoAssinado(RegistroAssinaturaInterna $registroAssinaturaInterna): Item
    {
        return $this->item($registroAssinaturaInterna->arquivo, new ArquivoTransformer());
    }

    public function transform(RegistroAssinaturaInterna $registroAssinaturaInterna)
    {
        return [
            'id' => $registroAssinaturaInterna->id,
            'documento_id' => $registroAssinaturaInterna->documento_id,
            'certificado_id' => $registroAssinaturaInterna->certificado_id,
            'arquivo_original_id' => $registroAssinaturaInterna->arquivo_original_id,
            'hash' => $registroAssinaturaInterna->hash,
            'data_criacao' => $registroAssinaturaInterna->created_at->toDateTimeString(),
            'data_hora_atual_completa' => $registroAssinaturaInterna->data_hora_atual_completa
        ];
    }
}
