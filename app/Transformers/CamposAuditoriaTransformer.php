<?php

namespace App\Transformers;

class CamposAuditoriaTransformer extends BaseTransformer
{
    public function transform($campos)
    {
        return [
            'campo' => $campos['campo'],
            'valor' => $campos['valor'],
        ];
    }
}
