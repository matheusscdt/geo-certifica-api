<?php

namespace App\Builders;

class UserLoginRequestBuilder
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): array
    {
        return [
            'email' => $this->data['email'],
            'password' => $this->data['password']
        ];
    }
}
