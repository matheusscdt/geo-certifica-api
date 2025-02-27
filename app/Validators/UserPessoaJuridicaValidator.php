<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;

class UserPessoaJuridicaValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator, Request $request)
    {
        $request->merge(['cnpj' => sanitizeCnpj($request->cnpj ?? '')]);

        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'nome' => ['required', 'max:255'],
                'cnpj' => ['required', 'max:14', 'cnpj', 'unique:pessoa_juridica,cnpj'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'min:6', 'confirmed'],
                'password_confirmation' => ['required']
            ],
            ValidatorInterface::RULE_UPDATE => [
                'nome' => ['sometimes', 'max:255'],
                'cnpj' => ['sometimes', 'max:14'],
                'email' => ['sometimes', 'email', 'unique:users,email,' . $request->id],
                'password' => ['sometimes', 'min:6', 'confirmed'],
                'password_confirmation' => ['sometimes']
            ]
        ];

        $this->messages = [
            'cnpj.unique' => 'O CNPJ informado já está cadastrado.',
        ];

        parent::__construct($validator);
    }
}
