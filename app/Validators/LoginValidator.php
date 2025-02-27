<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\Contracts\ValidatorInterface;

class LoginValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator)
    {
        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'email' => ['required', 'email'],
                'password' => ['required']
            ],
            ValidatorInterface::RULE_UPDATE => [
            ]
        ];

        parent::__construct($validator);
    }

}
