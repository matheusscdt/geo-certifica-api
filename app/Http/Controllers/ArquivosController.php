<?php

namespace App\Http\Controllers;

use App\Enums\RelatedUploadEnum;
use App\Services\ApiService;
use App\Services\ArquivoService;
use Illuminate\Http\Request;

class ArquivosController extends ApiController
{
    protected function service(): ApiService
    {
        return app(ArquivoService::class);
    }

    public function upload($relacionamentoNome, $relacionamentoId, Request $request)
    {
        return $this->service()->upload(
            RelatedUploadEnum::from($relacionamentoNome),
            $relacionamentoId,
            $request
        );
    }

    public function listar($relacionamentoNome, int $relacionamentoId)
    {
        return $this->service()->listar(RelatedUploadEnum::from($relacionamentoNome), $relacionamentoId);
    }

    public function download($arquivoId)
    {
        return $this->service()->download($arquivoId);
    }
}
