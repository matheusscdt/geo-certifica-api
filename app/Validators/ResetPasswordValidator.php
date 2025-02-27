<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class ResetPasswordValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'token' => ['required'],
                'email' => ['required', 'email', 'exists:users,email'],
                'password' => ['required', 'min:6', 'confirmed'],
                'password_confirmation' => ['required']
            ]
        ];

        parent::__construct($validator);
    }
}
