<?php

namespace App\Transformers;

use App\Models\Documento;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class DocumentoTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'arquivos',
        'pasta',
        'destinatarios',
        'mensagem',
        'configuracao',
        'statusDocumento',
        'registrosAssinaturaInterna'
    ];

    public function includeRegistrosAssinaturaInterna(Documento $documento): Collection
    {
        return $this->collection($documento->registrosAssinaturaInterna, new RegistroAssinaturaInternaTransformer());
    }

    public function includePasta(Documento $documento): Item
    {
        return $this->item($documento->pasta, new PastaTransformer());
    }

    public function includeArquivos(Documento $documento): Collection
    {
        return $this->collection($documento->arquivos, new ArquivoTransformer());
    }

    public function includeDestinatarios(Documento $documento): Collection
    {
        return $this->collection($documento->destinatarios, new DestinatarioTransformer());
    }

    public function includeMensagem(Documento $documento): ?Item
    {
        return $documento->mensagem ? $this->item($documento->mensagem, new MensagemTransformer()) : null;
    }

    public function includeConfiguracao(Documento $documento): ?Item
    {
        return $documento->configuracao ? $this->item($documento->configuracao, new ConfiguracaoTransformer()) : null;
    }

    public function includeStatusDocumento(Documento $documento): Item
    {
        return $this->item($documento->status_documento, new EnumTransformer());
    }

    public function transform(Documento $documento)
    {
        return [
            'id' => $documento->id,
            'pasta_id' => $documento->pasta_id,
            'status_documento' => $documento->status_documento,
            'data_alteracao' => $documento->updated_at->toDateTimeString(),
            'quantidade_assinado' => $documento->quantidade_assinado,
            'todos_destinatarios_assinaram' => $documento->todos_destinatarios_assinaram,
            'data_exclusao' => $documento->deleted_at?->toDateTimeString()
        ];
    }
}
