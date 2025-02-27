<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class MensagemValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'documento_id' => ['required', 'uuid', 'exists:documento,id', 'unique:mensagem,documento_id'],
                'assunto' => ['required'],
                'mensagem' => ['required']
            ],
            ValidatorInterface::RULE_UPDATE => [
                'assunto' => ['required'],
                'mensagem' => ['required']
            ]
        ];

        parent::__construct($validator);
    }
}
