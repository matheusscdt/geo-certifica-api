<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class AutorizacaoValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'destinatario_id' => ['required', 'uuid', 'exists:destinatario,id']
            ]
        ];

        parent::__construct($validator);
    }
}
