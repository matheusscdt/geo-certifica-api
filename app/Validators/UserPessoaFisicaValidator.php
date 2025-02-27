<?php

namespace App\Validators;

use App\Models\Convite;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;

class UserPessoaFisicaValidator extends BaseLaravelValidator
{
    public function __construct(Factory $validator, Request $request)
    {
        $request->merge(['cpf' => sanitizeCpf($request->cpf ?? '')]);

        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'convite_id' => ['nullable', 'uuid', 'exists:convite,id', function ($attribute, $value, $fail) {
                    if (Convite::where('id', $value)->where('aceite', true)->exists()) {
                        $fail('O Convite selecionado já foi aceito.');
                    }
                }],
                'nome' => ['required', 'max:255'],
                'cpf' => ['required', 'max:14', 'cpf', 'unique:pessoa_fisica,cpf'],
                'data_nascimento' => ['required', 'date'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'min:6', 'confirmed'],
                'password_confirmation' => ['required']
            ],
            ValidatorInterface::RULE_UPDATE => [
                'nome' => ['sometimes', 'max:255'],
                'data_nascimento' => ['sometimes', 'date'],
                'email' => ['sometimes', 'email', 'unique:users,email,' . $request->id],
                'password' => ['sometimes', 'min:6', 'confirmed'],
                'password_confirmation' => ['sometimes']
            ]
        ];

        $this->messages = [
            'cpf.unique' => 'O CPF informado já está cadastrado.',
        ];

        parent::__construct($validator);
    }
}
