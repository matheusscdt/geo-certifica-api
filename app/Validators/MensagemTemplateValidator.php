<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class MensagemTemplateValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'nome' => ['required'],
                'assunto' => ['required'],
                'mensagem' => ['required']
            ],
            ValidatorInterface::RULE_UPDATE => [
                'nome' => ['sometimes'],
                'assunto' => ['sometimes'],
                'mensagem' => ['sometimes']
            ]
        ];

        parent::__construct($validator);
    }
}
