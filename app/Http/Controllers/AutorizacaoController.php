<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\AutorizacaoService;
use Illuminate\Http\Request;

class AutorizacaoController extends ApiController
{
    protected function service(): ApiService
    {
        return app(AutorizacaoService::class);
    }

    public function validarCodigo(Request $request)
    {
        return $this->service()->validarCodigo($request);
    }

    public function gerarCodigoPorAssinatura($assinaturaId)
    {
        return $this->service()->gerarCodigoPorAssinatura($assinaturaId);
    }
}
