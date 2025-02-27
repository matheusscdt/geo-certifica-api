<?php

namespace App\Builders;

class CertificadoRequestBuilder
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    private function concatUnidadeOrganizacional(): string
    {
        return join(' - ', $this->data['cert_info']['subject']['OU']);
    }

    public function build(): array
    {
        return [
            'perfil_id' => $this->data['perfil_id'],
            'nome' => $this->data['cert_info']['subject']['CN'],
            'organizacao' => $this->data['cert_info']['subject']['O'],
            'unidade_organizacional' => $this->concatUnidadeOrganizacional(),
            'data_validade_inicio' => convertTimestampToDate($this->data['cert_info']['validFrom_time_t']),
            'data_validade_fim' => convertTimestampToDate($this->data['cert_info']['validTo_time_t']),
            'password' => $this->data['password'],
            'info' => $this->data['cert_info'],
            'content_file' => encryptFileBase64($this->data['arquivo']->getContent()),
            'selecionado' => (boolean)$this->data['selecionado']
        ];
    }
}
