<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class UserPerfilValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_UPDATE => [
                'ativo' => 'sometimes|boolean',
                'gestor' => 'sometimes|boolean',
                'pastas_id' => ['sometimes', 'array', 'exists:pasta,id'],
            ]
        ];

        parent::__construct($validator);
    }
}
