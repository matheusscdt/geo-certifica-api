<?php

namespace App\Transformers;

class EnumTransformer extends BaseTransformer
{
    public function transform($enum)
    {
        return [
            'value' => $enum->value,
            'label' => $enum->label()
        ];
    }
}
