<?php

namespace App\Builders;

class ConviteUpdateRequestBuilder
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): array
    {
        return [
            'data_aceite' => $this->data['data_aceite'] ?? null,
            'aceite' => $this->data['aceite']
        ];
    }
}
