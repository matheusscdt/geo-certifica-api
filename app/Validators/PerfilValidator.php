<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class PerfilValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'arquivo' => ['required', 'file', 'mimetypes:image/jpeg,image/png,image/gif'],
                'perfil_id' => ['nullable', 'uuid', 'exists:perfil,id']
            ],
            ValidatorInterface::RULE_UPDATE => [
                'nome' => ['sometimes', 'max:255'],
                'perfil_id' => ['nullable', 'uuid', 'exists:perfil,id']
            ]
        ];

        parent::__construct($validator);
    }
}
