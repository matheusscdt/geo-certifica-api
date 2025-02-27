<?php

namespace App\Builders;

use Illuminate\Http\Request;

class AssinaturaRequestBuilder
{
    protected array $data;

    protected Request $request;
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->request = request();
    }

    public function build(): array
    {
        return [
            'autorizacao_id' => $this->data['autorizacao_id'],
            'cpf' => $this->data['cpf'],
            'data_nascimento' => $this->data['data_nascimento'],
            'data_assinatura' => now(),
            'ip_address' => $this->request->ip(),
            'dispositivo' => $this->request->userAgent()
        ];
    }
}
