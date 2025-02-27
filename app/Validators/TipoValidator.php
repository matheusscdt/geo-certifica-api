<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class TipoValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'descricao' => ['required', 'max:255'],
                'perfil_id' => ['nullable', "uuid", 'exists:perfil,id']
            ],
            ValidatorInterface::RULE_UPDATE => [
                'descricao' => ['sometimes', 'max:255'],
                'perfil_id' => ['nullable', "uuid", 'exists:perfil,id']
            ]
        ];

        parent::__construct($validator);
    }
}
