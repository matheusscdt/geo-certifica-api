<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class AssinaturaValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'destinatario_id' => ['required', 'uuid', 'exists:destinatario,id'],
                'cpf' => ['required', 'cpf'],
                'data_nascimento' => ['required', 'date']
            ]
        ];

        $this->messages = [
            'autorizacao_id.unique' => 'Assinatura já foi realizada.'
        ];

        parent::__construct($validator);
    }
}
