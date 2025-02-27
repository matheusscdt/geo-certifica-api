<?php

namespace App\Transformers;

use App\Models\Certificado;
use League\Fractal\Resource\Item;

class CertificadoTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'arquivo'
    ];

    public function includeArquivo(Certificado $certificado): ?Item
    {
        return $certificado->arquivo ? $this->item($certificado->arquivo, new ArquivoTransformer()) : null;
    }

    public function transform(Certificado $certificado)
    {
        return [
            'id' => $certificado->id,
            'perfil_id' => $certificado->perfil_id,
            'nome' => $certificado->nome,
            'organizacao' => $certificado->organizacao,
            'unidade_organizacional' => $certificado->unidade_organizacional,
            'data_validade_inicio' => $certificado->data_validade_inicio->toDateTimeString(),
            'data_validade_fim' => $certificado->data_validade_fim->toDateTimeString(),
            'dias_vencimento' => $certificado->dias_vencimento,
            'selecionado' => $certificado->selecionado
        ];
    }
}
