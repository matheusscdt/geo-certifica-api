<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class ConviteValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'perfil_id' => ['nullable', 'uuid', 'exists:perfil,id'],
                'nome' => ['required', 'max:255'],
                'email' => ['required', 'email'],
                'pastas_id' => ['required', 'array', 'exists:pasta,id'],
                'gestor' => ['required', 'boolean'],
            ],
            ValidatorInterface::RULE_UPDATE => []
        ];

        parent::__construct($validator);
    }
}
