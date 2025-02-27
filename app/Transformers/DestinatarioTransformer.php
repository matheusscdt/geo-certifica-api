<?php

namespace App\Transformers;

use App\Models\Destinatario;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class DestinatarioTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'documento',
        'agenda',
        'tipo',
        'autorizacoes',
        'autorizacaoValida'
    ];

    public function includeDocumento(Destinatario $destinatario): Item
    {
        return $this->item($destinatario->documento, new DocumentoTransformer());
    }

    public function includeAgenda(Destinatario $destinatario): Item
    {
        return $this->item($destinatario->agenda, new AgendaTransformer());
    }

    public function includeTipo(Destinatario $destinatario): Item
    {
        return $this->item($destinatario->tipo, new TipoTransformer());
    }

    public function includeAutorizacoes(Destinatario $destinatario): Collection
    {
        return $this->collection($destinatario->autorizacoes, new AutorizacaoTransformer());
    }

    public function includeAutorizacaoValida(Destinatario $destinatario): ?Item
    {
        return $destinatario->autorizacaoValida() ? $this->item($destinatario->autorizacaoValida(), new AutorizacaoTransformer()) : null;
    }

    public function transform(Destinatario $destinatario)
    {
        return [
            'id' => $destinatario->id,
            'tipo_id' => $destinatario->tipo_id,
            'documento_id' => $destinatario->documento_id,
            'agenda_id' => $destinatario->agenda_id,
            'assinatura_realizada' => $destinatario->assinatura_realizada
        ];
    }
}
