<?php

namespace App\Transformers;

use App\Models\MensagemTemplate;
use League\Fractal\Resource\Item;

class MensagemTemplateTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'perfil'
    ];

    public function includePerfil(MensagemTemplate $mensagemTemplate): Item
    {
        return $this->item($mensagemTemplate->perfil, new PerfilTransformer());
    }

    public function transform(MensagemTemplate $mensagemTemplate)
    {
        return [
            'id' => $mensagemTemplate->id,
            'perfil_id' => $mensagemTemplate->perfil_id,
            'nome' => $mensagemTemplate->nome,
            'assunto' => $mensagemTemplate->assunto,
            'mensagem' => $mensagemTemplate->mensagem
        ];
    }
}
