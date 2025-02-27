<?php

namespace App\Builders;

class UserCreateRequestBuilder
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): array
    {
        return [
            'pessoa' => [
                'nome' => $this->data['nome'] ?? null,
            ],
            'pessoaFisica' => [
                'cpf' => $this->data['cpf'] ?? null,
                'data_nascimento' => $this->data['data_nascimento'] ?? null
            ],
            'pessoaJuridica' => [
                'cnpj' => $this->data['cnpj'] ?? null
            ],
            'perfil' => [
                'nome' => $this->data['nome'] ?? null
            ],
            'agenda' => [
                'nome' => $this->data['nome'] ?? null,
                'email' => $this->data['email'] ?? null,
            ],
            'user' => [
                'email' => $this->data['email'] ?? null,
                'password' => $this->data['password'] ?? null
            ]
        ];
    }
}
