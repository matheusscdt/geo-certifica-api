<?php

namespace App\Validators;

use App\Rules\ArquivoPfxRule;
use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class CertificadoValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'arquivo' => ['required', 'file', new ArquivoPfxRule()],
                'password' => ['required'],
                'selecionado' => ['required', 'boolean']
            ],
            ValidatorInterface::RULE_UPDATE => [
                'selecionado' => ['required', 'boolean']
            ]
        ];


        parent::__construct($validator);
    }
}
