<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class CodigoValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'destinatario_id' => ['required', 'uuid', 'exists:destinatario,id'],
                'codigo' => ['required', 'integer']
            ]
        ];

        parent::__construct($validator);
    }
}
