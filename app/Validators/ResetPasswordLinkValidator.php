<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class ResetPasswordLinkValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'email' => ['required', 'email', 'exists:users,email'],
            ]
        ];

        parent::__construct($validator);
    }
}
