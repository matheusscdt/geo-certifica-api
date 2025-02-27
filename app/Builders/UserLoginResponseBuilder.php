<?php

namespace App\Builders;

class UserLoginResponseBuilder
{
    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function build(): array
    {
        return [
            'token' => $this->token,
            'type' => 'bearer',
            'expires' => auth()->factory()->getTTL() * 60
        ];
    }

}
