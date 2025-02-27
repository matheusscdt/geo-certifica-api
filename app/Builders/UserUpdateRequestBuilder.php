<?php

namespace App\Builders;

class UserUpdateRequestBuilder
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): array
    {
        return [
            'pessoa' => $this->filterNullable([
                'nome' => $this->data['nome'] ?? null,
            ]),
            'pessoaFisica' => $this->filterNullable([
                'cpf' => $this->data['cpf'] ?? null,
                'data_nascimento' => $this->data['data_nascimento'] ?? null
            ]),
            'pessoaJuridica' => $this->filterNullable([
                'cnpj' => $this->data['cnpj'] ?? null
            ]),
            'perfil' => $this->filterNullable([
                'nome' => $this->data['nome'] ?? null
            ]),
            'agenda' => $this->filterNullable([
                'nome' => $this->data['nome'] ?? null,
                'email' => $this->data['email'] ?? null,
            ]),
            'user' => $this->filterNullable([
                'email' => $this->data['email'] ?? null,
                'password' => $this->data['password'] ?? null
            ])
        ];
    }

    private function filterNullable(array $data): array
    {
        return array_filter($data, fn($value) => $value !== null);
    }
}
