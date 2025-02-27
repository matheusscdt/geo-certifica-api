<?php

namespace App\Validators;

use App\Models\User;
use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class UserValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'email' => ['required', 'email', 'exists:users,email', function ($attribute, $value, $fail) {
                    if (User::where('email', $value)->where('ativo', true)->exists()) {
                        $fail('O Usuário selecionado já foi ativado.');
                    }
                }],
                'codigo' => ['required', 'integer']
            ],
            ValidatorInterface::RULE_UPDATE => []
        ];

        parent::__construct($validator);
    }
}
