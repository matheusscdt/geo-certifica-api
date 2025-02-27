<?php

namespace App\Transformers;

use App\Models\Arquivo;

class ArquivoTransformer extends BaseTransformer
{
    public function transform(Arquivo $model)
    {
        return [
            'id' => $model->id,
            'nome' => $model->nome,
            'arquivo' => $model->arquivo,
            'extensao' => $model->extensao,
            'mime_type'=> $model->mime_type,
            'tamanho' => $model->tamanho,
            'link' => $model->url
        ];
    }
}
